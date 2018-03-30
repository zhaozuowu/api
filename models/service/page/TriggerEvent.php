<?php
/**
 * @name Service_Page_TriggerEvent
 * @desc Service_Page_TriggerEvent
 * @author huabang.xue@ele.me
 */

class Service_Page_TriggerEvent implements Orderui_Base_Page
{
    /**
     * @var Service_Data_TriggerEvent
     */
    protected $objData;

    /**
     * Service_Page_TriggerEvent constructor.
     */
    public function __construct()
    {
        $this->objData = new Service_Data_TriggerEvent();
    }

    /**
     * @param array $arrInput
     * @return array
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        $intClientId = $arrInput['client_id'];
        $strEventKey = $arrInput['event_key'];
        $arrData     = $arrInput['data'];
        $res = $this->objData->TriggerEvent($intClientId, $strEventKey, $arrData);
        return $res;
    }
}