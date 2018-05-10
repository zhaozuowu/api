<?php
/**
 * @name Omssys.php
 * @desc Omssys.php
 * @author yu.jin03@ele.me
 */

class Orderui_Lib_Omssys
{
    /**
     * 格式化oms系统信息
     * @param $arrResponseList
     * @return array
     */
    public static function formatOmsSysInfo($arrResponseList) {
        $arrOrderSysList = [];
        $arrResults = Orderui_Util_Utility::arrayToKeyValues($arrResponseList, 'order_system_id');
        foreach ($arrResults as $arrResponse) {
            $arrOrderSysList[] = [
                'order_system_id' => $arrResponse[0]['order_system_id'],
                'order_system_type' => $arrResponse[0]['order_system_type'],
                'business_form_order_id' => $arrResponse[0]['business_form_order_id'],
            ];
        }
        return $arrOrderSysList;
    }
}