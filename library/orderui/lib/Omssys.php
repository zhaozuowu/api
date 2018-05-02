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
        Bd_Log::trace('$arrResponseList :'. json_encode($arrResponseList));
        foreach ($arrResponseList as $arrResponse) {
            $arrOrderSysList[] = [
                'order_system_id' => $arrResponse['order_system_id'],
                'order_system_type' => $arrResponse['order_system_type'],
                'business_form_order_id' => $arrResponse['business_form_order_id'],
            ];
        }
        return $arrOrderSysList;
    }
}