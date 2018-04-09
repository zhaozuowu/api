<?php
/**
 * @name Service_Data_ShipmentOrder
 * @desc 运单相关逻辑
 * @author huabang.xue@ele.me
 * Date: 2018/3/26
 * Time: 下午4:31
 */
class Service_Data_ShipmentOrder
{
    /*
     * @var object
     */
    protected $objDaoRalNwmsOrder;
    /*
     * @var object
     */
    protected $objDaoWprcTms;
    /*
     * init object
     */
    public function __construct()
    {
        $this->objDaoRalNwmsOrder = new Dao_Ral_NWmsOrder();
        $this->objDaoWprcTms = new Dao_Wrpc_Tms();
    }

    /**
     * tms进行签收
     * @param $intLogisticsOrderId
     * @param $arrSignupSkus
     * @param $intBizType
     * @return array
     * @throws Orderui_BusinessError
     */
    public function signupShipmentOrder($intLogisticsOrderId, $arrSignupSkus, $intBizType)
    {
        //根据物流单号获取运单号
        $intShipmentOrderId = Model_Orm_BusinessFormOrder::getMapOrderIdBySourceOrderId($intLogisticsOrderId,
                                    Nscm_Define_OmsOrder::TMS_ORDER_TYPE_SHIPMENT);
        if (empty($intShipmentOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_MAP_ORDER_NOT_FOUND);
        }
        //调用tms接口进行运单签收
        $arrRet = $this->objDaoWprcTms->signupShipmentOrder($intShipmentOrderId,$intBizType, $arrSignupSkus);
        Bd_Log::trace(sprintf("method[%s] signup arrRet[%s]", __METHOD__, json_encode($arrRet)));
        if (!empty($arrRet['errno']) || 0 != $arrRet['errno']) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_TMS_SIGNUP_SHIPMENT_ORDER_FAILED);
        }
        $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_ACCEPT_ALL;
        if ('SIGNED_PART' == $arrRet['data']['status']) {
            $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_ACCEPT_PART;
        }
        if ('REJECT' == $arrRet['data']['status']) {
            $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_REJECT_ALL;
        }
        $arrRejectSkus = $arrRet['data']['rejectMap'];
        return [$intShipmentOrderId, $intSignupStatus, $arrRejectSkus];
    }

    /**
     * @desc 接收签收运单请求并转发wms
     * @param int $intShipmentOrderId
     * @param int $intSignupStatus
     * @param array $arrSinupSkus
     * @param array $arrOffShelfSkus
     * @param array $arrAdjustSkus
     * @param array $arrRejectSkus
     * @return array
     * @throws Orderui_BusinessError
     */
    public function signupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, $arrSinupSkus, $arrOffShelfSkus, $arrAdjustSkus, $arrRejectSkus)
    {
        $arrRet = [
            'shipment_order_id' => strval($intShipmentOrderId),
            'result' => false,
        ];
        $arrShipmentOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intShipmentOrderId,
            Nscm_Define_OmsOrder::TMS_ORDER_TYPE_SHIPMENT);
        if (empty($arrShipmentOrder)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_SHIPMENT_ORDER);
        }

        $intStockOutOrderId = $arrShipmentOrder['parent_order_id'];
        $arrStockoutOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intStockOutOrderId, Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);
        if (empty($arrStockoutOrder)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_STOCKOUT_ORDER);
        }
        //存储库存调整sku
        if (!empty($arrAdjustSkus)) {
            $intBusinessFormOrderId = intval($arrShipmentOrder['business_form_order_id']);
            $objBusinessFormOrder = Model_Orm_BusinessFormOrder::getBusinessFormOrderByBusinessOrderId($intBusinessFormOrderId);
            if (!empty($objBusinessFormOrder)) {
                $arrBusinessFormOrderExt = json_decode($objBusinessFormOrder['business_form_ext'], true);
                $arrBusinessFormOrderExt['adjust_stock_skus'] = $arrAdjustSkus;
                $arrRow['business_form_ext'] = json_encode($arrBusinessFormOrderExt);
                $objBusinessFormOrder->update($arrRow);
            }
        }
        //转发nwms
        $arrParam = [
            'stockout_order_id' => $intStockOutOrderId,
            'signup_status'      => $intSignupStatus,
            'signup_skus'       => $arrSinupSkus,
        ];
        $strCmd = Orderui_Define_Cmd::CMD_SIGNUP_STOCKOUT_ORDER;
        $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, strval($intStockOutOrderId));
        if (false == $ret) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
        }
        //若签收状态是拒收或者有拒收商品或有下架商品则创建销退入库单
        if ($intSignupStatus == Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_REJECT_ALL
            || !empty($arrRejectSkus) || !empty($arrOffShelfSkus)) {
            $this->SendStockinSkuInfoToWmq($intShipmentOrderId, $intStockOutOrderId, $arrRejectSkus, $arrOffShelfSkus);
        }
        $arrRet['result'] = true;
        return $arrRet;
    }
    /*
     * 创建销退入库单的sku信息发送wmq
     * @param int $intShipmentOrderId
     * @param int $intStockOutOrderId
     * @param array $arrSinupSkus
     * @param array $arrOffShelfSkus
     * @return bool
     */
    public function SendStockinSkuInfoToWmq($intShipmentOrderId, $intStockOutOrderId, $arrRejectSkus, $arrOffShelfSkus)
    {
        $arrSkuList = [];
        foreach ($arrOffShelfSkus as $intSkuId => $intSkuAmount) {
            $arrSkuList[] = [
                'sku_id'     => $intSkuId,
                'sku_amount' => $intSkuAmount,
            ];
        }
        foreach ($arrRejectSkus as $intSkuId => $intSkuAmount) {
            $arrSkuList[] = [
                'sku_id'     => $intSkuId,
                'sku_amount' => $intSkuAmount,
            ];
        }
        $arrParamCreateStockin = [
            'stockout_order_id' => $intStockOutOrderId,
            'shipment_order_id' => $intShipmentOrderId,
            'sku_info_list'     => json_encode($arrSkuList),
            'stockin_order_remark' => '',
        ];
        $strCmdStockin = Orderui_Define_Cmd::CMD_CREATE_RETURN_STOCKIN_ORDER;
        $wmqRet = Orderui_Wmq_Commit::sendWmqCmd($strCmdStockin, $arrParamCreateStockin, strval($intStockOutOrderId));
        if (false == $wmqRet) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmdStockin));
        }
        return true;
    }

    /*
     * 签收wms出库单
     */
    public function SignupStockoutOrder($arrSignupData)
    {
        return $this->objDaoRalNwmsOrder->signupStockoutOrder($arrSignupData);
    }

    /*
     * 创建销退入库单
     */
    public function CreateSalesReturnStockinOrder($arrData)
    {
        $intStockoutOrderId = intval($arrData['stockout_order_id']);
        $arrRet = $this->objDaoRalNwmsOrder->CreateSalesReturnStockinOrder($arrData);
        if (!empty($arrRet)) {
            $intStockinOrderId = intval($arrRet['result']['stockin_order_id']);
            $arrStockoutOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intStockoutOrderId, Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);
            if (!empty($arrStockoutOrder)) {
                $intBusinessFormOrderId = intval($arrStockoutOrder['business_form_order_id']);
                $intOrderSystemId = intval($arrStockoutOrder['order_system_id']);
                $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
                $arrRow = [
                    'order_system_detail_id' => $intOrderSystemDetailId,
                    'order_system_id'        => $intOrderSystemId,
                    'business_form_order_id' => $intBusinessFormOrderId,
                    'order_type'             => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_IN,
                    'order_id'               => $intStockinOrderId,
                    'parent_order_id'        => $intStockoutOrderId,
                ];
                $ret = Model_Orm_OrderSystemDetail::insert($arrRow);
                if(false === $ret) {
                    Bd_Log::warning(sprintf("method[%s] insert stockin order fail stockin_order_id[%s]", __METHOD__, $intStockinOrderId));
                }
            }

        }
        return true;
    }
}