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
        $intShipmentOrderId = intval($arrInput['shipment_order_id']);
        $intSignupStatus = intval($arrInput['signup_status']);
        $arrSinupSkus = $arrInput['signup_skus'];
        $arrOffShelfSkus = $arrInput['offshelf_skus'];
        $arrAdjustSkus = $arrInput['adjust_skus'];
        return $this->objData->SignupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, $arrSinupSkus, $arrOffShelfSkus, $arrAdjustSkus);
    }
}