<?php
/**
 * @name Action_Service_OutsideTriggerEvent
 * @desc unify all event
 * @author bochao.lv@ele.me
 */

class Action_Service_OutsideTriggerEvent extends Orderui_Base_ServiceAction
{

    /**
     * init page
     */
    function myConstruct()
    {
        $this->objPage = new Service_Page_TriggerEvent();
    }

    /**
     * @throws Wm_Error
     */
    public function beforeMyExecute()
    {
        $arrInput = $this->arrRequest;
        Bd_Log::debug('validator input: ' . json_encode($arrInput));
        $strEventKey = $this->arrRequest['event_key'];
        $arrFormat = Orderui_Define_OutsideEvent::OUTSIDE_EVENT_VALIDATE[$strEventKey];
        $arrContent = $this->arrRequest['params'];
        $arrValidateResult = $this->validate($arrFormat, $arrContent);
        $this->arrFilterResult = [
            'event_key' => $strEventKey,
            'data' => $arrValidateResult,
        ];
        Bd_Log::debug('validator output: ' . json_encode($this->arrFilterResult));
    }

    /**
     * @param array $data
     * @return array
     */
    public function format($data)
    {
        return [
            'result' => $data,
        ];
    }
}