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
     * 业态订单创建并拆分转发
     * @param $arrInput
     * @return
     * @throws Nscm_Exception_Error
     * @throws Orderui_Error
     * @throws Wm_Error
     */
    public function execute($arrInput) {
        $arrInput['business_form_order_id'] = Orderui_Util_Utility::generateBusinessFormOrderId();
        $this->objDsBusinessFormOrder->createBusinessFormOrder($arrInput);
        $arrOrderList = $this->objDsBusinessFormOrder->splitBusinessOrder($arrInput);
        $res = $this->objDsBusinessFormOrder->distributeOrder($arrOrderList);
        return $res;
    }
}