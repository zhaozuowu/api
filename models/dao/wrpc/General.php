<?php
/**
 * @name Dao_Wrpc_General
 * @desc Dao_Wrpc_General
 * @author bochao.lv@ele.me
 */

class Dao_Wrpc_General
{
    /**
     * general call
     * @param string $strAppId
     * @param string $strNamespace
     * @param string $strServiceName
     * @param string $arrMeta
     * @param string $strFunction
     * @param mixed $mixData
     * @return mixed
     */
    public static function call($strAppId, $strNamespace, $strServiceName, $arrMeta, $strFunction, $mixData)
    {
        Bd_Log::debug(sprintf('wrpc call, app_id[%s], namespace[%s], service_name[%s], meta[%s],' .
            ' function[%s], data[%s]', $strAppId, $strNamespace, $strServiceName, json_encode($arrMeta), $strFunction,
            json_encode($mixData)));
        $objWrpcClient = new Bd_Wrpc_Client($strAppId, $strNamespace, $strServiceName);
        if (is_array($arrMeta) && !empty($arrMeta)) {
            $objWrpcClient->setMeta($arrMeta);
        }
        $result = $objWrpcClient->$strFunction($mixData);
        Bd_Log::debug(sprintf('wrpc call response: %s', json_encode($result)));
        return $result;
    }
}