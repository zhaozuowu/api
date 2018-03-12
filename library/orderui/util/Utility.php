<?php
/**
 * @name Order_Util_Utility
 * @desc Order_Util_Utility
 * @author yu.jin03@ele.me
 */
class Orderui_Util_Utility
{
    /**
     * generate business form order id
     * @return int
     * @throws Wm_Error
     */
    public static function generateBusinessFormOrderId() 
    {
        return NScm_Lib_IdGenerator::sequenceDateNumber();
    }


    /**
     * generate oms order code
     * @return int
     * @throws Wm_Error
     */
    public static function generateOmsOrderCode()
    {
        return Nscm_Lib_IdGenerator::sequenceDateNumber();
    }

    /**
     * transfer array to key value pair
     * @param array $arr
     * @param string $primary_key
     * @return array
     */
    public static function arrayToKeyValue($arr, $primary_key) {
        if (empty($arr) || empty($primary_key)) {
            return array();
        }
        $arrKeyValue = array();
        foreach ($arr as $item) {
            if(isset($item[$primary_key])) {
                $arrKeyValue[$item[$primary_key]] = $item;
            }
        }
        return $arrKeyValue;
    }

    /**
     * transfer array to key values pair
     * @param array $arr
     * @param string $primary_key
     * @return array
     */
    public static function arrayToKeyValues($arr, $primary_key) {
        if (empty($arr) || empty($primary_key)) {
            return array();
        }
        $arrKeyValue = array();
        foreach ($arr as $item) {
            if(isset($item[$primary_key])) {
                $arrKeyValue[$item[$primary_key]][] = $item;
            }
        }
        return $arrKeyValue;
    }
}