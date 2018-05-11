<?php
/**
 * @name ShelfObverse.php
 * @desc ShelfObverse.php
 * @author yu.jin03@ele.me
 */

class Service_Data_Router_ShelfObverse extends Orderui_Base_OrderRouter
{
    /**
     * @var Service_Data_NWmsOrder
     */
    protected $objNwmsOrder;

    /**
     * Service_Data_Router_ShelfObverse constructor.
     */
    public function __construct()
    {
        $this->objNwmsOrder = new Service_Data_NWmsOrder();
    }

    /**
     * 货架正向拆分
     * @param $arrBusinessOrderInfo
     * @param $intBusinessOrderId
     * @return array
     * @throws Wm_Error
     */
    protected function splitOrder($arrBusinessOrderInfo, $intBusinessOrderId)
    {
        $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();
        $arrOrderSysDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
                'request_info' => $arrBusinessOrderInfo,
            ],
        ];
        return $arrOrderSysDetailList;
    }

    /**
     * 货架正向转发
     * @param $arrOrderList
     * @param $intSourceOrderId
     * @return array
     * @throws Nscm_Exception_Error
     */
    protected function distributeOrder($arrOrderList, $intSourceOrderId)
    {
        $ret = [];
        $objNwmsOrder = new Service_Data_NWmsOrder();
        foreach ($arrOrderList as $arrOrderInfo) {
            $intOrderSysType = $arrOrderInfo['order_system_type'];
            if (Orderui_Define_Const::ORDER_SYS_NWMS == $intOrderSysType) {
                $ret[] = [
                    'result' => $objNwmsOrder->createNWmsOrder($arrOrderInfo['request_info']),
                    'order_system_id' => $arrOrderInfo['order_system_id'],
                    'order_system_type' => $arrOrderInfo['order_system_type'],
                    'business_form_order_id' => $arrOrderInfo['business_form_order_id'],
                    'warehouse_id' => $arrOrderInfo['request_info']['warehouse_id'],
                    'logistics_order_id' => $arrOrderInfo['request_info']['logistics_order_id'],
                    'order_type' => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
                ];
            }
        }
        return $ret;
    }

}