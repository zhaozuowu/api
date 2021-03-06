<?php
/**
 * @name Service_Page_SignupShipmentOrder
 * @desc 对运单进行签收
 * @author huabang.xue@ele.me
 * Date: 2018/3/26
 * Time: 下午4:23
 */
class Service_Page_SignupShipmentOrder
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
        $this->objData = new Service_Data_ShipmentOrder();
    }

    /*
     * @desc 对运单进行签收
     * @param arr $arrInput
     * @return true
     */
    public function execute($arrInput)
    {
        $intLogisticsOrderId = intval($arrInput['logistics_order_id']);
        $arrSkuEvents = Orderui_Event::filterEventTypes($arrInput['skus_event']);
        $arrSignupSkus = $arrSkuEvents['signup_skus'];
        $arrOffShelfSkus = $arrSkuEvents['off_skus'];
        $arrAdjustSkus = $arrSkuEvents['adjust_skus'];
        $intBizType = $arrInput['biz_type'];
        list($intShipmentOrderId, $intSignupStatus, $arrRejectSkus) = $this->objData->signupShipmentOrder($intLogisticsOrderId, $arrSignupSkus, $intBizType);
        return $this->objData->signupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, $arrSignupSkus, $arrOffShelfSkus, $arrAdjustSkus, $arrRejectSkus, $intBizType);
    }
}