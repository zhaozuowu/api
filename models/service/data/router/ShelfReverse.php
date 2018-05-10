<?php
/**
 * @name ShelfReverse.php
 * @desc ShelfReverse.php
 * @author yu.jin03@ele.me
 */
class Service_Data_Router_ShelfReverse extends Orderui_Base_OrderRouter
{
    /**
     * @var Dao_Wrpc_Tms
     */
    protected $objDaoWrpcTms;

    /**
     * Service_Data_Router_ShelfReverse constructor.
     */
    public function __construct()
    {
        $this->objDaoWrpcTms = new Dao_Wrpc_Tms();
    }

    /**
     * 拆分货架逆向单
     * @param $arrBusinessOrderInfo
     * @param $intBusinessOrderId
     * @return array|mixed
     * @throws Wm_Error
     */
    protected function splitOrder($arrBusinessOrderInfo, $intBusinessOrderId)
    {
        $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();
        $arrOrderSysDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_TMS,
                'business_form_order_id' => $intBusinessOrderId,
                'request_info' => $arrBusinessOrderInfo,
            ],
        ];
        return $arrOrderSysDetailList;
    }

    /**
     * 转发货架逆向单
     * @param $arrOrderList
     * @param $intSourceOrderId
     * @return mixed|void
     * @throws Nscm_Exception_Error
     */
    protected function distributeOrder($arrOrderList, $intSourceOrderId)
    {
        $ret = [];
        $objWrpcTms = new Dao_Wrpc_Tms();
        foreach ($arrOrderList as $arrOrderInfo) {
                $ret[] = [
                    'result' => $objWrpcTms->createShipmentOrder($arrOrderInfo['request_info']),
                    'order_system_id' => $arrOrderInfo['order_system_id'],
                    'order_system_type' => $arrOrderInfo['order_system_type'],
                    'business_form_order_id' => $arrOrderInfo['business_form_order_id'],
                    'warehouse_id' => $arrOrderInfo['request_info']['warehouse_id'],
                    'logistics_order_id' => $arrOrderInfo['request_info']['logistics_order_id'],
                    'order_type' => Nscm_Define_OmsOrder::TMS_ORDER_TYPE_SHIPMENT,
            ];
        }
        return $ret;
    }

}