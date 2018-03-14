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
     * @param  array $arrInput
     * @return array
     * @throws Nscm_Exception_Error
     * @throws Orderui_Error
     * @throws Wm_Error
     * @throws Exception
     */
    public function execute($arrInput) {
        $arrInput['business_form_order_id'] = Orderui_Util_Utility::generateBusinessFormOrderId();
        $res = $this->objDsBusinessFormOrder->splitBusinessOrder($arrInput);
        $this->objDsBusinessFormOrder->createBusinessFormOrder($arrInput);
        return $res;
    }
}