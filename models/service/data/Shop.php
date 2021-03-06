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
    protected $objDaoWprcNwms;
    /*
     * @var object
     */
    protected $objDaoRedis;

    /*
     * init object
     */
    public function __construct()
    {
        $this->objDaoWprcNwms = new Dao_Wrpc_Nwms();
        $this->objDaoRedis = new Dao_Redis_BusinessOrder();
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

    /**
     * 接收出库单拣货信息，通知门店
     * @param $strStockoutOrderId
     * @param $arrPickupSkuInfoList
     * @return array
     * @throws Orderui_BusinessError
     */
    public function updateStockoutOrderSkuPickupInfo($strStockoutOrderId, $arrPickupSkuInfoList)
    {
        // 门店不需要前缀，传给门店唯一识别的id
        $intNwmsStockoutOrderId = intval(Orderui_Util::trimStockoutOrderIdPrefix($strStockoutOrderId));
        if (empty($intNwmsStockoutOrderId) || empty($arrPickupSkuInfoList)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        if (0 >= $intNwmsStockoutOrderId) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        // 校验数量
        foreach ($arrPickupSkuInfoList as $skuInfo) {
            $intSkuId = intval($skuInfo['sku_id']);
            $intSkuAmount = intval($skuInfo['sku_amount']);
            if( (0 >= $intSkuId) || (0 > $intSkuAmount)) {
                Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
            }
        }
        $arrOrderSysDetail = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intNwmsStockoutOrderId, Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);
        if (empty($arrOrderSysDetail)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::ORDER_SYS_DETAIL_NOT_EXITED);
        }

        $intOrderSysDetailId = $arrOrderSysDetail['order_system_detail_id'];
        //修改出库单数量
        foreach ($arrPickupSkuInfoList as $skuInfo) {
            $intSkuId = intval($skuInfo['sku_id']);
            $intSkuAmount = intval($skuInfo['sku_amount']);
            $arrCondition = [
                'sku_id' => $intSkuId,
                'order_id' => $intNwmsStockoutOrderId,
                'order_system_detail_id' => $intOrderSysDetailId,
            ];
            $objOrderSysDetailSku = Model_Orm_OrderSystemDetailSku::findOne($arrCondition);
            if (!is_null($objOrderSysDetailSku)) {
                $objOrderSysDetailSku->sku_amount = $intSkuAmount;
                $objOrderSysDetailSku->update();
            }
        }
        $objWrpcShop = new Dao_Wrpc_Shop();
        return $objWrpcShop->updateStockoutOrderSkuPickupInfo($intNwmsStockoutOrderId, $arrPickupSkuInfoList);
    }

}