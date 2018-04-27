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

    }

    public function notifyNwmsOrderCreate($arrOrderList)
    {
        $arrParams = $this->getNotifyNwmsOrderCreateParams($arrOrderList);
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