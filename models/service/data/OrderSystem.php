<?php
/**
 * @name Service_Data_OrderSystem
 * @desc oms系统订单
 * @author hang.song02@ele.me
 */

class Service_Data_OrderSystem
{
    /**
     * 组建order system 信息
     * @param  array $arrResponseList
     * @return array
     */
    public function assembleOrderSystemDbData($arrResponseList)
    {
        $arrOrderSysList = [];
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