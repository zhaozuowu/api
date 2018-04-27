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
     * 批量创建nwms订单
     * @param $arrOrderList
     * @return
     * @throws Orderui_BusinessError
     */
    public function batchCreateBusinessOrder($arrOrderList)
    {
        $arrBatchCreateParams = $this->getBatchCreateParams($arrOrderList);
        $arrRet = $this->objWrpcService->batchCreateBusinessOrder($arrBatchCreateParams);
        if (empty($arrRet) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] params[%s] arrRet[%s]",
                __METHOD__, json_encode($arrBatchCreateParams), json_encode($arrRet)));
            Orderui_BusinessError::throwException($arrRet['errno'], $arrRet['errmsg']);
        }
        return $arrRet['data'];
    }

    /**
     * 拼接批量创建出库单的参数
     * @param $arrOrderList
     * @return array
     */
    protected function getBatchCreateParams($arrOrderList)
    {
        $arrBatchCreateParams = [];
        $arrOrderInfos = [];
        if (empty($arrOrderList)) {
            return [];
        }
        foreach ((array)$arrOrderList as $arrOrderItem) {
            $arrBusinessOrderInfo = $arrOrderItem['request_info'];
            $arrBatchCreateParams = $arrBusinessOrderInfo;
            $arrOrderInfoItem = [];
            $arrOrderInfoItem['logistics_order_id'] = $arrBusinessOrderInfo['order_system_id'];
            $arrOrderInfoItem['skus'] = $arrBusinessOrderInfo['skus'];
            $arrOrderInfoItem['warehouse_id'] = $arrBusinessOrderInfo['warehouse_id'];
            $arrOrderInfos[] = $arrOrderInfoItem;
        }
        $arrBatchCreateParams['order_info'] = $arrOrderInfos;
        return $arrBatchCreateParams;
    }
}