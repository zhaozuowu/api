<?php
/**
 * @name Orderui_Error
 * @desc Error
 * @auth wanggang01@iwaimai.baidu.com
 */
class Orderui_Error extends Wm_Error
{
    /**
     * @param integer $intErrorCode
     * @param string  $strErrorMsg
     * @param array   $arrErrorData
     * @throws Orderui_Error
     */
    public static function throwException($intErrorCode, $strErrorMsg = '', $arrErrorData = [])
    {
        throw new self($intErrorCode, $strErrorMsg, $arrErrorData);
    }
}
