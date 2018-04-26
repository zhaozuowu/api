<?php
/**
 * @name Service_Data_Shop
 * @desc 门店相关逻辑
 * @author huabang.xue@ele.me
 * Date: 2018/3/26
 * Time: 下午4:31
 */
class Service_Data_Shop
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
     * 计算签收差异数量和状态
     * @param $intStockOutOrderId
     * @param $arrSinupSkus
     * @return array
     * @throws Orderui_BusinessError
     */
    public function getSignupStatus($intStockOutOrderId, $arrSinupSkus)
    {
        $arrRejectSkus = [];
        $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_ACCEPT_ALL;
        $arrStockoutOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intStockOutOrderId, Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);
        if (empty($arrStockoutOrder)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_STOCKOUT_ORDER);
        }
        $intOrderSystemDetailId = $arrStockoutOrder['order_system_detail_id'];
        $arrConds = [
            'order_system_detail_id' => $intOrderSystemDetailId,
            'is_delete'              => Orderui_Define_Const::NOT_DELETE,
            'sku_exception'          => '',
        ];
        $arrStockoutSkus = Model_Orm_OrderSystemDetailSku::findRows(['sku_id', 'sku_amount'], $arrConds);
        if (!empty($arrStockoutSkus)) {
            foreach ($arrStockoutSkus as $arrSku) {
                $intSkuId = $arrSku['sku_id'];
                $intSkuAmount = $arrSku['sku_amount'];
                if (!isset($arrSinupSkus[$intSkuId])) {
                    $arrRejectSkus[$intSkuId] = $intSkuAmount;
                } elseif ($intSkuAmount > $arrSinupSkus[$intSkuId]) {
                    $arrRejectSkus[$intSkuId] = $intSkuAmount - $arrSinupSkus[$intSkuId];
                }
            }
        }

        if (empty($arrSinupSkus) || array_sum($arrSinupSkus) == 0) {
            $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_REJECT_ALL;
        } elseif (!empty($arrRejectSkus)) {
            $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_ACCEPT_PART;
        }
        return [$intSignupStatus, $arrRejectSkus];
    }

    /**
     * 门店签收
     * @param $intStockOutOrderId
     * @param $intSignupStatus
     * @param $arrSinupSkus
     * @param $arrRejectSkus
     * @param $intBizType
     * @return bool
     */
    public function signupByInput($intStockOutOrderId, $intSignupStatus, $arrSinupSkus, $arrRejectSkus, $intBizType)
    {
        $intShipmentOrderId = 0;

        //转发nwms
        $arrSkusList = [];
        foreach ($arrSinupSkus as $strSkuId => $intAmount) {
            $arrSkusList[] = [
                $strSkuId => $intAmount,
            ];
        }
        $arrParam = [
            'stockout_order_id' => $intStockOutOrderId,
            'signup_status'      => $intSignupStatus,
            'signup_skus'       => $arrSkusList,
        ];
        $strCmd = Orderui_Define_Cmd::CMD_SIGNUP_STOCKOUT_ORDER;
        $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, strval($intStockOutOrderId));
        if (false == $ret) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
        }
        //若签收状态是拒收或者有拒收商品则创建销退入库单
        if ($intSignupStatus == Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_REJECT_ALL
            || !empty($arrRejectSkus)) {
            $this->sendStockinSkuInfoToWmq($intShipmentOrderId, $intStockOutOrderId, $arrRejectSkus, $intBizType);
        }

        return true;
    }

    /**
     * 创建销退入库单的sku信息发送wmq
     * @param $intShipmentOrderId
     * @param $intStockOutOrderId
     * @param $arrRejectSkus
     * @param $intBizType
     * @return bool
     */
    public function sendStockinSkuInfoToWmq($intShipmentOrderId, $intStockOutOrderId, $arrRejectSkus, $intBizType)
    {
        $arrSkuList = [];
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
            'stockin_order_source' => $intBizType,
            'stockin_order_remark' => '',
        ];
        $strCmdStockin = Orderui_Define_Cmd::CMD_CREATE_RETURN_STOCKIN_ORDER;
        $wmqRet = Orderui_Wmq_Commit::sendWmqCmd($strCmdStockin, $arrParamCreateStockin, strval($intStockOutOrderId));
        if (false == $wmqRet) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmdStockin));
        }
        return true;
    }

}