<?php
/**
 * @name Service_Page_Business_CreateBusinessFormOrder
 * @desc Service_Page_Business_CreateBusinessFormOrder
 * @author yu.jin03@ele.me
 */
class Service_Page_Business_CreateBusinessFormOrder
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * init object
     */
    public function __construct() {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * 业态订单创建
     * @param $arrInput
     */
    public function execute($arrInput) {
        $this->objDsBusinessFormOrder->createBusinessFormOrder($arrInput);
    }
}