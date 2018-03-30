<?php
/**
 * @name Dao_Ral_General
 * @desc Dao_Ral_General
 * @author bochao.lv@ele.me
 */

class Dao_Ral_General
{

    /**
     * return type raw
     */
    const RETURN_TYPE_RAW = 1;

    /**
     * return type json array
     */
    const RETURN_TYPE_JSON_ARRAY = 2;

    /**
     * return type json std class
     */
    const RETURN_TYPE_JSON_STD = 3;

    /**
     * http get
     * @param string $strUrl
     * @param string $strServiceName
     * @param mixed $mixParams
     * @param int $intReturnType
     * @param array $arrPayload
     * @param array $arrHeaders
     * @return bool|mixed
     */
    public static function httpGet($strUrl, $strServiceName, $mixParams,
                                   $intReturnType = self::RETURN_TYPE_RAW, $arrPayload = [], $arrHeaders = [])
    {
        return self::httpCall($strUrl, $strServiceName, $intReturnType, 'get', $mixParams, $arrPayload, $arrHeaders);
    }
    /**
     * http post
     * @param string $strUrl
     * @param string $strServiceName
     * @param mixed $mixParams
     * @param int $intReturnType
     * @param array $arrPayload
     * @param array $arrHeaders
     * @return bool|mixed
     */
    public static function httpPost($strUrl, $strServiceName, $mixParams,
                                    $intReturnType = self::RETURN_TYPE_RAW, $arrPayload = [], $arrHeaders = [])
    {
        return self::httpCall($strUrl, $strServiceName, $intReturnType, 'post', $mixParams, $arrPayload, $arrHeaders);
    }

    /**
     * @param string $strUrl
     * @param string $strServiceName
     * @param int $intReturnType
     * @param string $strMethod
     * @param mixed $mixParam
     * @param array $arrPayload
     * @param array $arrHeaders
     * @return bool|mixed
     */
    private static function httpCall($strUrl, $strServiceName, $intReturnType, $strMethod,
                                     $mixParam, $arrPayload = [], $arrHeaders = [])
    {
        $arrHeaders['pathinfo'] = $strUrl;
        $result = ral($strServiceName, $strMethod, $arrPayload, $mixParam, $arrHeaders);
        if (false === $result) {
            Bd_Log::warning(sprintf('ral error! errno[%s], errmsg[%s], protocol_status[%s]',
                ral_get_errno(), ral_get_error(), ral_get_protocol_code()));
            return false;
        }
        switch ($intReturnType) {
            case self::RETURN_TYPE_JSON_ARRAY:
                return json_decode($result, true);
            case self::RETURN_TYPE_JSON_STD:
                return json_decode($result, false);
            case self::RETURN_TYPE_RAW:
            default:
                return $result;
        }
    }
}