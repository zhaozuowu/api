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
     * @var Service_Data_OmsDetailOrder
     */
    protected $objDsOmsDetail;
    /**
     * @var Service_Data_OrderSystem
     */
    protected $objDsOmsSys;
    /**
     * @var Service_Data_OrderSystem
     */
    protected $objDsNwmsOrder;

    /**
     * init object
     */
    public function __construct() {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
        $this->objDsOmsDetail = new Service_Data_OmsDetailOrder();
        $this->objDsOmsSys = new Service_Data_OrderSystem();
        $this->objDsNwmsOrder = new Service_Data_NWmsOrder();
    }

    /**
     * 业态订单创建并拆分转发
     * @param  array $arrInput
     * @return array
     * @throws Nscm_Exception_Error
     * @throws Wm_Error
     * @throws Exception
     */
    public function execute($arrInput) {
        //$this->objDsBusinessFormOrder->checkAuthority($arrInput['business_form_key'], $arrInput['business_form_token']);
        $arrInput['business_form_order_way'] = Orderui_Define_BusinessFormOrder::ORDER_WAY_OBVERSE;
        $arrResponseList = $this->objDsBusinessFormOrder->createOrder($arrInput);
        if (0 != $arrResponseList[0]['result']['error_no']) {
            Orderui_BusinessError::throwException($arrResponseList[0]['result']['error_no'], $arrResponseList[0]['result']['error_msg']);
        }
        return $arrResponseList[0]['result']['result'];
    }
}