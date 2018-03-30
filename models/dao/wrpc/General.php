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
     * @param string $strRoutingKey
     * @param string $strFunction
     * @param mixed $mixData
     * @return mixed
     */
    public static function call($strAppId, $strNamespace, $strServiceName, $strRoutingKey, $strFunction, $mixData)
    {
        Bd_Log::debug(sprintf('wrpc call, app_id[%s], namespace[%s], service_name[%s], routing_key[%s],' .
            ' function[%s], data[%s]', $strAppId, $strNamespace, $strServiceName, $strRoutingKey, $strFunction,
            json_encode($mixData)));
        $objWrpcClient = new Bd_Wrpc_Client($strAppId, $strNamespace, $strServiceName);
        $objWrpcClient->setMeta(['routing-key' => $strRoutingKey]);
        $result = $objWrpcClient->$strFunction($mixData);
        Bd_Log::debug(sprintf('wrpc call response: %s' . json_encode($result)));
        return $result;
    }
}