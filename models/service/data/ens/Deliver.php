<?php
/**
 * @name Service_Data_Ens_Deliver
 * @desc Service_Data_Ens_Deliver
 * @author bochao.lv@ele.me
 */

class Service_Data_Ens_Deliver
{
    /**
     * @param $strEvent
     * @param $intBranch
     * @return bool
     */
    private function checkEventBranch($strEvent, $intBranch)
    {
        return isset(Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch]);
    }

    /**
     * @param $strEvent
     * @param $arrData
     * @param $intBranch
     * @return array|Orderui_Struct_WrpcInfo
     */
    private function formatData($strEvent, $arrData, $intBranch)
    {
        $strFunction = Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch]['format'];
        $callableFunc = ['Service_Data_Ens_Format', $strFunction];
        if (!is_callable($callableFunc)) {
            $callableFunc[1] = 'defaultFormat';
        }
        return call_user_func($callableFunc, $arrData);
    }

    /**
     * call ral
     * @param string $strEvent
     * @param array $arrData
     * @param int $intBranch
     * @return bool|mixed
     */
    private function callRal($strEvent, $arrData, $intBranch)
    {
        $arrConf = Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch];
        $strService = $arrConf['service'];
        $strUrl = $arrConf['url'];
        return Dao_Ral_General::httpPost($strUrl, $strService, $arrData);
    }

    /**
     * call wrpc
     * @param string $strEvent
     * @param Orderui_Struct_WrpcInfo $objData
     * @param int $intBranch
     * @return string mixed
     */
    private function callWrpc($strEvent, $objData, $intBranch)
    {
        if (!($objData instanceof Orderui_Struct_WrpcInfo)) {
            trigger_error('ens wrpc call use Orderui_Struct_WrpcInfo');
        }
        $arrConf = Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch];
        $strAppId = $arrConf['app_id'];
        $strNamespace = $arrConf['namespace'];
        $strService = $arrConf['service'];
        $callableFunc = $arrConf['call'];
        $arrMeta = $objData->meta;
        $arrData = $objData->data;
        $arrRet = Dao_Wrpc_General::call($strAppId, $strNamespace, $strService, $arrMeta, $callableFunc, $arrData);
        return $arrRet;
    }

    /**
     * call dao deliver
     * @param string $strEvent
     * @param array $arrData
     * @param int $intBranch
     * @return bool|mixed|string
     */
    private function callDaoDeliver($strEvent, $arrData, $intBranch)
    {
        $intCall = Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch]['type'];
        if (Orderui_Define_Event::CALL_RAL == $intCall) {
            return $this->callRal($strEvent, $arrData, $intBranch);
        } else if (Orderui_Define_Event::CALL_WRPC == $intCall) {
            return $this->callWrpc($strEvent, $arrData, $intBranch);
        } else {
            return true;
        }
    }

    /**
     * format result
     * @param string $strEvent
     * @param int $intBranch
     * @param string $rawResult
     * @return mixed
     */
    private function formatResult($strEvent, $intBranch, $rawResult)
    {
        $strFunction = Orderui_Define_EventCall::BRANCH[$strEvent][$intBranch]['result'];
        $callableFunc = ['Service_Data_Ens_Result', $strFunction];
        if (!is_callable($callableFunc)) {
            $callableFunc[1] = 'defaultResult';
        }
        return call_user_func($callableFunc, $rawResult);
    }

    /**
     * deliver
     * @param string $strEvent
     * @param array $arrData
     * @param int $intBranch
     * @throws Orderui_BusinessError
     */
    public function deliver($strEvent, $arrData, $intBranch)
    {
        // check event branch
        if (!$this->checkEventBranch($strEvent, $intBranch)) {
            Bd_Log::trace('this branch is invalid');
            return;
        }
        // format data
        $mixData = $this->formatData($strEvent, $arrData, $intBranch);
        // call dao deliver
        Bd_Log::debug('ENS_REQUEST: ' . json_encode($mixData));
        $rawResult = $this->callDaoDeliver($strEvent, $mixData, $intBranch);
        Bd_Log::debug('ENS_RESULT: ' . json_encode($rawResult));
        if (true === $rawResult) {
            Bd_Log::trace('call type error');
            return;
        }
        // format result
        $boolResult = $this->formatResult($strEvent, $intBranch, $rawResult);
        if (!$boolResult) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::ERR__RAL_ERROR);
        }
        return ;
    }
}