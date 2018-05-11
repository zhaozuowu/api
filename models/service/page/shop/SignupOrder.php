<?php
/**
 * @name Service_Page_Shop_SignupOrder
 * @desc 门店签收
 * @author huabang.xue@ele.me
 * Date: 2018/3/26
 * Time: 下午4:23
 */
class Service_Page_Shop_SignupOrder
{
    /*
     * @var objData
     */
    protected $objData;
    /*
     * init object
     */
    public function __construct()
    {
        $this->objData = new Service_Data_Shop();
    }

    /*
     * @desc 门店签收
     * @param arr $arrInput
     * @return true
     */
    public function execute($arrInput)
    {
        $intStockoutOrderId = intval($arrInput['stockout_order_id']);
        $arrSkuEvents = Orderui_Event::filterEventTypes($arrInput['skus_event']);
        $arrSignupSkus = $arrSkuEvents['signup_skus'];
        $intBizType = $arrInput['biz_type'];
        list($intSignupStatus, $arrRejectSkus) = $this->objData->getSignupStatus($intStockoutOrderId, $arrSignupSkus);
        return $this->objData->signupByInput($intStockoutOrderId, $intSignupStatus, $arrSignupSkus, $arrRejectSkus, $intBizType);
    }
}