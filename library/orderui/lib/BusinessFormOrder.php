<?php
/**
 * @name BusinessFormOrder.php
 * @desc BusinessFormOrder.php
 * @author yu.jin03@ele.me
 */
class Orderui_Lib_BusinessFormOrder
{
    /**
     * 根据nwms订单创建结果和入参拼接oms子单创建参数
     * @param $arrNwmsResponseList
     * @param $arrInput
     * @return array
     * @throws Wm_Error
     */
    public static function formatBusinessInfo($arrNwmsResponseList, $arrInput) {
        $arrBusinessOrderInfo = Orderui_Lib_Nwmsorder::formatNwmsOrderException($arrNwmsResponseList, $arrInput);
        $arrOrderSysListDb = Orderui_Lib_Omssys::formatOmsSysInfo($arrNwmsResponseList);
        $arrOrderSysDetailListDb = Orderui_Lib_Ordersystemdetail::formatOrderSystemDetailInfo($arrNwmsResponseList, $arrBusinessOrderInfo['skus'], $arrInput['business_form_order_type']);
        return [$arrBusinessOrderInfo, $arrOrderSysListDb, $arrOrderSysDetailListDb];
    }

    /**
     * 根据nwms订单创建结果和入参拼接oms子单创建参数
     * @param $arrNwmsResponseList
     * @param $arrInput
     * @return array
     * @throws Wm_Error
     */
    public static function formatBusinessInfoForReverse($arrNwmsResponseList, $arrInput) {
        $arrBusinessOrderInfo = Orderui_Lib_Nwmsorder::formatNwmsOrderException($arrNwmsResponseList, $arrInput);
        $arrOrderSysListDb = Orderui_Lib_Omssys::formatOmsSysInfo($arrNwmsResponseList);
        $arrOrderSysDetailListDb = Orderui_Lib_Ordersystemdetail::formatOrderSystemDetailInfoForReverse($arrNwmsResponseList, $arrBusinessOrderInfo['skus']);
        return [$arrBusinessOrderInfo, $arrOrderSysListDb, $arrOrderSysDetailListDb];
    }
}