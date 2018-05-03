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
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_ISS,
            Orderui_Define_Wrpc::NAMESPACE_ISS,
            Orderui_Define_Wrpc::SERVICE_NAME_ISS);
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
            $arrChildOrderInfo['receipts_id'] = $arrOrderInfo['stockout_order_id'];
            $arrChildOrderInfo['order_split_time'] = time();
            $arrChildOrderInfo['receipts_type'] = 1;
            $arrChildOrderInfo['warehouse_id'] = $arrOrderInfo['warehouse_id'];
            $arrChildOrderInfo['warehouse_name'] = $arrOrderInfo['warehouse_name'];
            $arrChildOrderInfo['exception_sku_list'] = $this->getChildOrderSkusException($arrOrderInfo['exceptions']);
            $arrChildOrderInfo['receipts_details'] = [];
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
}