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
     * wrcp service
     * @var Bd_Wrpc_Client
     */
    private $objWrpcStockinService;

    /**
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::NWMS_APP_ID,
            Orderui_Define_Wrpc::NWMS_NAMESPACE,
            Orderui_Define_Wrpc::NWMS_SERVICE_NAME);
        $this->objWrpcStockinService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::NWMS_APP_ID,
            Orderui_Define_Wrpc::NWMS_NAMESPACE, Orderui_Define_Wrpc::NWMS_SERVICE_NAME_STOCKIN);
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

    /**
     * 批量创建NWms销退入库单
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Orderui_BusinessError
     */
    public function batchCreateStockinOrder($arrBusinessOrderInfo)
    {
        $strRoutingKey = sprintf("loc=%s", $arrBusinessOrderInfo['business_form_order_id']);
        $this->objWrpcStockinService->setMeta(["routing-key" => $strRoutingKey]);
        $arrParams = $this->formatBatchCreateStockinParams($arrBusinessOrderInfo);
        Bd_Log::trace(sprintf('method[%s] parameters %s', __METHOD__, json_encode($arrParams)));
        $arrRet = $this->objWrpcStockinService->batchCreateStockInOrder($arrParams);
        Bd_Log::trace(sprintf("method[%s] batch create nwms sale return stockin order[%s]", __METHOD__, json_encode($arrRet)));
        if (empty($arrRet['data']) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("batch_create_stockin_order_fail, logistics_order_id[%s], error_no[%s], error_msg[%s]"
                , $arrParams['logistics_order_id'], $arrRet['errno'], $arrRet['errmsg']));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_BATCH_CREATE_SALE_RETURN_STOCKIN_ORDER_FAIL);
        }
        return $arrRet['data'];
    }

    /*
     * 批量创建nwms订单
     * @param $arrOrderList
     * @return
     * @throws Orderui_BusinessError
     */
    public function batchCreateBusinessOrder($arrOrderList)
    {
        $arrBatchCreateParams = $this->getBatchCreateParams($arrOrderList);
        $arrRet = $this->objWrpcService->batchCreateBusinessOrder($arrBatchCreateParams);
        Bd_Log::trace(sprintf("method[%s] params[%s] arrRet[%s]", __METHOD__,
            json_encode($arrBatchCreateParams), json_encode($arrRet)));
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
            $arrOrderInfoItem['logistics_order_id'] = $arrOrderItem['order_system_detail_id'];
            $arrOrderInfoItem['skus'] = $arrBusinessOrderInfo['skus'];
            $arrOrderInfoItem['warehouse_id'] = $arrBusinessOrderInfo['warehouse_id'];
            $arrOrderInfos[] = $arrOrderInfoItem;
        }
        $arrBatchCreateParams['order_info'] = $arrOrderInfos;
        return [ 'objData' => $arrBatchCreateParams];
    }

    /**
     * 拼装批量创建销退入库单参数
     * @param $arrOrderList
     * @return array
     */
    public function formatBatchCreateStockinParams($arrOrderList)
    {
        Bd_Log::trace('$arrOrderList'.json_encode($arrOrderList));
        $arrBatchReturnsInfo = [];
        $intLogisticsOrderId = 0;
        foreach ($arrOrderList as $arrOrder) {
            $arrRequestInfo = $arrOrder['request_info'];
            $intLogisticsOrderId = $arrRequestInfo['logistics_order_id'];
            $arrSkus = [];
            foreach ($arrRequestInfo['skus'] as $skus) {
                $arrSkus[] = [
                    'sku_id' => intval($skus['sku_id']),
                    'sku_amount' => intval($skus['order_amount']),
                ];
            }
            $arrCustomerInfo = [
                'customer_id' => $arrRequestInfo['customer_id'],
                'customer_name' => $arrRequestInfo['customer_name'],
                'customer_contactor' => $arrRequestInfo['customer_contactor'],
                'customer_contact' => $arrRequestInfo['customer_contact'],
                'customer_address' => $arrRequestInfo['customer_address'],
            ];

            $arrBatchReturnsInfo[] = [
                'business_form_order_id' => $arrOrder['business_form_order_id'],
                'order_system_id' => $arrOrder['order_system_id'],
                'order_system_detail_id' => $arrOrder['order_system_detail_id'],
                'order_system_type' => $arrOrder['order_system_type'],
                'logistics_order_id' => $arrRequestInfo['logistics_order_id'],
                'warehouse_id' => $arrRequestInfo['warehouse_id'],
                'warehouse_name' => $arrRequestInfo['warehouse_name'],
                'stockin_order_source' => $arrRequestInfo['business_form_order_type'],
                'stockin_order_remark' => $arrRequestInfo['business_form_order_remark'],
                'sku_info_list' => $arrSkus,
                'customer_info' => $arrCustomerInfo,
            ];
        }
        return [
            'batch_returns_info' => $arrBatchReturnsInfo,
            'logistics_order_id' => $intLogisticsOrderId,
        ];
    }
}