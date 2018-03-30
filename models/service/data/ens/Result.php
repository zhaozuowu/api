<?php
/**
 * @name Service_Data_Ens_Result
 * @desc Service_Data_Ens_Result
 * @author bochao.lv@ele.me
*/

class Service_Data_Ens_Result
{
    /**
     * default result
     * @param string $strInput
     * @return bool
     */
    public static function defaultResult($strInput)
    {
        return $strInput !== false;
    }

    /**
     * default json result
     * @param string $strInput
     * @return bool
     */
    public static function defaultJsonResult($strInput)
    {
        $result = json_decode(strval($strInput), true);
        if ($result !== false && !empty($result['error_no'])) {
            return false;
        }
        return true;
    }
}