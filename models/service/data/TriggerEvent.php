<?php
/**
 * @name Service_Data_TriggerEvent
 * @desc 接入事件相关逻辑
 * @author huabang.xue@ele.me
 */

class Service_Data_TriggerEvent
{
    /**
     * 接入事件
     * @param  int $intClientId
     * @param  string $strEventKey
     * @param  array $arrData
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function TriggerEvent($intClientId, $strEventKey, $arrData)
    {
        $arrParam = [
            'client_id' => $intClientId,
            'event_key'      => $strEventKey,
            'data'       => $arrData,
        ];
        $strCmd = Orderui_Define_Cmd::CMD_EVENT_SYSTEM;
        $strKey = md5(json_encode($arrData));
        $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, $strKey, Orderui_Define_Cmd::OMS_ENS_TOPIC);
        if (false == $ret) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_TRIGGER_EVENT_FAIL);
        }
        return true;
    }
}