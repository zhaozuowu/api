<?php
/**
 * @name Service_Page_CheckReverseBusinessFormOrder
 * @desc 盘点订单
 * @author hang.song02@ele.me
 */

class Service_Page_CheckReverseBusinessFormOrder
{
    /**
     * @var $objData
    */
    protected $objData;
    /*
     * init object
     */
    public function __construct()
    {
        $this->objData = new Service_Data_BusinessFormOrder();
    }

    /**
     * @param array $arrInput
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        return $this->objData->checkReverseBusinessFormOrder($arrInput['logistics_order_id'], $arrInput['shelf_infos'], $arrInput['skus']);
    }
}