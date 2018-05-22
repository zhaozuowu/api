<?php
/**
 * @name Dao_Ral_InsideCallEns
 * @desc Dao_Ral_InsideCallEns
 * @author bochao.lv@ele.me
 */

class Dao_Ral_InsideCallEns
{
    /**
     * call
     * @param $strEventKey
     * @param $arrData
     * @throws Orderui_BusinessError
     */
    public static function call($strEventKey, $arrData) {
        if (isset(Orderui_Define_EventCall::BRANCH[$strEventKey])) {
            $arrParam = [
                'event_key'      => $strEventKey,
                'data'       => $arrData,
            ];
            $strCmd = Orderui_Define_Cmd::CMD_EVENT_SYSTEM;
            $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, strval($strEventKey),
                Orderui_Define_Cmd::OMS_ENS_TOPIC);
            if (false == $ret) {
                Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
                Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_TRIGGER_EVENT_FAIL);
            }
        } else {
            trigger_error('you should add Orderui_Define_OutsideEvent::OUTSIDE_EVENT_VALIDATE before call ens');
        }
    }
}