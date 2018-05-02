<?php
/**
 * @name Dao_Wrpc_Nwms
 * @desc wrpc方式调用nwms
 * @author hang.song02@ele.me
 */

class Dao_Wrpc_Nwms
{
    /**
     * wrcp service
     * @var Bd_Wrpc_Client
     */
    private $objWrpcService;

    /**
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::NWMS_APP_ID,
            Orderui_Define_Wrpc::NWMS_NAMESPACE,
            Orderui_Define_Wrpc::NWMS_SERVICE_NAME);
    }

    /**
     * 创建NWms订单
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Orderui_BusinessError
     */
    public function createNWmsOrder($arrBusinessOrderInfo)
    {
        $strRoutingKey = sprintf("loc=%s", $arrBusinessOrderInfo['logistics_order_id']);
        $this->objWrpcService->setMeta(["routing-key"=>$strRoutingKey]);
        $arrRet = $this->objWrpcService->createBusinessFormOrder($arrBusinessOrderInfo);
        Bd_Log::trace(sprintf("method[%s] create nwms order[%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR);
        }
        return $arrRet;
    }

    /**
     * 更新NWMS退货修正销退入库单计划入库数
     * @param  array $arrStockinOrderInfo
     * @return array
     * @throws Orderui_BusinessError
     */
    public function updateNwmsStockInOrderSkuPlanAmount($arrStockinOrderInfo)
    {
        $objNwmsStockinWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::NWMS_APP_ID,
            Orderui_Define_Wrpc::NWMS_NAMESPACE,
            Orderui_Define_Wrpc::SERVICE_NAME_NWMS_STOCKIN);
        $strRoutingKey = sprintf("loc=%s", $arrStockinOrderInfo['stockin_order_id']);
        $arrRet = $objNwmsStockinWrpcService->updateStockInOrderSkuPlanAmount($arrStockinOrderInfo);
        Bd_Log::trace(sprintf("method[%s] update nwms stockin order sku plan amount [%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_UPDATE_NWMS_STOCKIN_ORDER_SKU_PLAN_AMOUNT_FAIL);
        }
        return $arrRet;
    }
}