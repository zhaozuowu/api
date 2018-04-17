<?php
/**
 * @name Service_Data_Ens_Format
 * @desc Service_Data_Ens_Format
 * @author bochao.lv@ele.me
*/

class Service_Data_Ens_Format
{
    /**
     * default format
     * @param array $arrInput
     * @return array
     */
    public static function defaultFormat($arrInput)
    {
        return $arrInput;
    }

    /**
     * format nwms finish order
     * @param $arrInput
     * @return array
     */
    public static function formatNwmsFinishOrder($arrInput)
    {
        return [
            'stockout_order_id' => $arrInput['stockout_order_id'],
            'signup_status' => $arrInput['signup_status'],
            'signup_skus' => $arrInput['signup_skus'],
        ];
    }
}