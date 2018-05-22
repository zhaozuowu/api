<?php
/**
 * @name Orderui_Wmq_Commit
 * @desc send wmq cmd
 * @author jinyu02@iwaimai.baidu.com
 */
class Orderui_Wmq_Commit extends Wm_Lib_Wmq_Commit {
    
    /**
     * send wmq cmd
     * @param string $strCmd
     * @param array $arrParams
     * @param string $key
     * @param string $Topic
     * @return integer
     */
    public static function sendWmqCmd($strCmd, $arrParams, $strKey = '', $strTopic = '') {
        $arrWmqConfig = Orderui_Define_Cmd::DEFAULT_WMQ_CONFIG;
        if (!empty($strKey)) {
            $arrWmqConfig['Key'] = $strKey;
        }
        if (!empty($strTopic)) {
            $arrWmqConfig['Topic'] = $strTopic;
        }
        Bd_Log::debug(sprintf('send wmq, cmd[%s], params[%s], config[%s]', $strCmd, json_encode($arrParams),
            json_encode($arrWmqConfig)));
        $ret = self::sendCmd($strCmd, $arrParams, $arrWmqConfig);
        Bd_Log::debug(sprintf('send wmq response: %s', json_encode($ret)));
        if (false === $ret) {
            Bd_Log::warning(sprintf('send_wmq_fail, cmd[%s] config[%s]', $strCmd, json_encode($arrWmqConfig)));
        }
        return $ret;
    }
}
