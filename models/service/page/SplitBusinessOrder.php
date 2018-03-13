<?php
/**
 * @name Service_Page_SplitBusinessOrder
 * @desc Service_Page_SplitBusinessOrder
 * @author hang.song02@ele.me
 */

class Service_Page_SplitBusinessOrder implements Orderui_Base_Page
{
    /**
     * @var Service_Data_BusinessOrder
     */
    protected $objData;

    /**
     * Service_Page_SplitBusinessOrder constructor.
     */
    public function __construct()
    {
        $this->objData = new Service_Data_BusinessOrder();
    }

    /**
     * @param array $arrInput
     * @return array
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function execute($arrInput)
    {
        $res = $this->objData->splitBusinessOrder($arrInput);
        return $res;
    }
}