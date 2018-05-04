<?php
/**
 * @name Iss.php
 * @desc Iss.php
 * @author yu.jin03@ele.me
 */

class Dao_Wrpc_Iss
{
    /**
     * @var Bd_Wrpc_Client
     */
    private $objWrpcService;

    /**
     * Dao_Wrpc_Iss constructor.
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_SHOP,
            Orderui_Define_Wrpc::NAMESPACE_SHOP, Orderui_Define_Wrpc::SERVICE_NAME_SHOP);
    }

    /**
     * 通知门店正向订单创建结果
     * @param $arrOrderList
     * @throws Orderui_BusinessError
     */
    public function notifyNwmsOrderCreate($arrOrderList)
    {
        $arrParams = $this->getNotifyNwmsOrderCreateParams($arrOrderList);
        $arrRet = $this->objWrpcService->omsOrderGoods($arrParams);
        if (empty($arrRet['errno']) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s]", __METHOD__, json_encode($arrRet)));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_ISS_CREATE_RESULT_FAILED);
        }
    }

    /**
     * @param $arrOrderList
     * @return
     * @throws Orderui_BusinessError
     */
    public function notifyNwmsReturnOrderCreate($arrOrderList)
    {
        $strRoutingKey = sprintf("business_form_order_id=%s", $arrOrderList[0]['business_form_order_id']);
        $arrParams = $this->getNotifyNwmsReturnOrderCreateParams($arrOrderList);
        Bd_Log::trace(sprintf('method[%s] call shop bookservice omsReturnOrderGoods request [%s]', __METHOD__, json_encode($arrParams)));
        $arrRet = $this->objWrpcService->omsReturnOrderGoods($arrParams);
        Bd_Log::trace(sprintf("method[%s] call shop bookservice omsReturnOrderGoods arrRet [%s]", __METHOD__, json_encode($arrRet)));
        if (empty($arrRet['data']) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_CREATE_SHOP_RETURN_ORDER_FAIL);
        }
        return $arrRet['data'];
    }

    /**
     * 拼接通知门店的参数
     * @param $arrOrderList
     * @return array
     */
    protected function getNotifyNwmsOrderCreateParams($arrOrderList)
    {
        $arrNwmsOrders = [];
        if (empty($arrOrderList)) {
            return $arrNwmsOrders;
        }
        $arrNwmsOrders['parent_receipts_id'] = intval($arrOrderList[0]['logistics_order_id']);
        $arrNwmsOrders['split_child_order_list'] = $this->getChildOrderListParams($arrOrderList);
        return $arrNwmsOrders;
    }

    /**
     * 拼接通知门店子单信息
     * @param $arrOrderList
     * @return array
     */
    protected function getChildOrderListParams($arrOrderList)
    {
        $arrChildOrders = [];
        foreach ((array)$arrOrderList as $arrOrderInfo) {
            $arrChildOrderInfo = [];
            $arrChildOrderInfo['receipts_id'] = intval($arrOrderInfo['result']['result']['stockout_order_id']);
            $arrChildOrderInfo['order_split_time'] = time();
            $arrChildOrderInfo['receipts_type'] = 1;
            $arrChildOrderInfo['warehouse_id'] = intval($arrOrderInfo['warehouse_id']);
            $arrChildOrderInfo['warehouse_name'] = strval($arrOrderInfo['warehouse_name']);
            $arrChildOrderInfo['exception_sku_list'] = $this->getChildOrderSkusException($arrOrderInfo['result']['exceptions']);
            $arrChildOrderInfo['receipts_detail'] = $this->getReceiptsDetail($arrOrderInfo['result']['result']['skus']);
            $arrChildOrders[] = $arrChildOrderInfo;
        }
        return $arrChildOrders;
    }

    /**
     * 拼接通知门店异常信息
     * @param $arrSkusException
     * @return array
     */
    protected function getChildOrderSkusException($arrSkusException)
    {
        $arrRetSkusException = [];
        foreach ((array)$arrSkusException as $arrSkusExceptionInfo) {
            $arrRetSkusExceptionInfo = [];
            $arrRetSkusExceptionInfo['sku_id'] = $arrSkusExceptionInfo['sku_id'];
            $arrRetSkusExceptionInfo['reason'] = $arrSkusExceptionInfo['exception_info'];
            $arrRetSkusException[] = $arrRetSkusExceptionInfo;
        }
        return $arrRetSkusException;
    }

    /**
     * 拼接通知门店sku信息
     * @param $arrSkus
     * @return array
     */
    protected function getReceiptsDetail($arrSkus)
    {
        $arrReceiptsDetail = [];
        foreach ((array)$arrSkus as $arrSkuInfo) {
            $arrReceiptDetailInfo = [];
            $arrReceiptDetailInfo['sku_id'] = $arrSkuInfo['sku_id'];
            $arrReceiptDetailInfo['count'] = $arrSkuInfo['distribute_amount'];
            $arrReceiptsDetail[] = $arrReceiptDetailInfo;
        }
        return $arrReceiptsDetail;
    }

    /**
     * 拼接通知门店的参数
     * @param $arrOrderList
     * @return array
     */
    protected function getNotifyNwmsReturnOrderCreateParams($arrOrderList)
    {
        $arrNwmsOrders = [];
        if (empty($arrOrderList)) {
            return $arrNwmsOrders;
        }
        Bd_Log::trace('arrOrderList:%s'.json_encode($arrOrderList));
        $arrNwmsOrders['parent_receipts_id'] = intval($arrOrderList[0]['result']['logistics_order_id']);
        $arrChildOrders = [];
        foreach ((array)$arrOrderList as $arrOrderInfo) {
            $arrOrderResult = $arrOrderInfo['result'];
            $arrRetSkusException = [];
            foreach ((array) $arrOrderResult['exceptions'] as $arrSkusExceptionInfo) {
                $arrRetSkusException[] = [
                    'sku_id' => $arrSkusExceptionInfo['sku_id'],
                    'count'  => $arrSkusExceptionInfo['order_amount'],
                    'reason' => $arrSkusExceptionInfo['exception_info'],
                ];
            }
            $arrRetSkus = [];
            foreach ((array) $arrOrderResult['skus'] as $arrSku) {
                $arrRetSkus[] = [
                    'sku_id' => $arrSku['sku_id'],
                    'count'  => $arrSku['order_amount'],
                    'delivery_price' => $arrSku['send_price'],
                ];
            }
            $arrChildOrderInfo = [];
            $arrChildOrderInfo['receipts_id'] = intval($arrOrderResult['stockin_order_id']);
            $arrChildOrderInfo['order_split_time'] = time();
            $arrChildOrderInfo['receipts_type'] = 1;
            $arrChildOrderInfo['warehouse_id'] = $arrOrderResult['warehouse_id'];
            $arrChildOrderInfo['warehouse_name'] = $arrOrderResult['warehouse_name'];
            $arrChildOrderInfo['exception_reason'] = $arrOrderResult['exception_reason'];
            $arrChildOrderInfo['exception_code'] = $arrOrderResult['exception_code'];
            $arrChildOrderInfo['exception_sku_list'] = $arrRetSkusException;
            $arrChildOrderInfo['receipts_details'] = $arrRetSkus;
            $arrChildOrders[] = $arrChildOrderInfo;
        }
        $arrNwmsOrders['split_child_order_list'] = $arrChildOrders;
        return $arrNwmsOrders;
    }
}