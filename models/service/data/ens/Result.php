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
     * @param array $arrResult
     * @return bool
     */
    public static function defaultJsonResult($arrResult)
    {
        if ($arrResult !== false && !empty($arrResult['errno'])) {
            return false;
        }
        return true;
    }

}