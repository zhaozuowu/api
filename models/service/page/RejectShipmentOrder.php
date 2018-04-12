<?php
/**
 * @name RejectShipmentOrder.php
 * @desc RejectShipmentOrder.php
 * @author yu.jin03@ele.me
 */
class Service_Page_RejectShipmentOrder
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
     * @desc 对运单进行拒收
     * @param arr $arrInput
     * @return true
     */
    public function execute($arrInput)
    {
        $intShipmentOrderId = intval($arrInput['shipment_order_id']);
        $arrRejectSkus = Orderui_Event::transferArrayToMap($arrInput['reject_skus']);
        $intSignupStatus = Orderui_Define_ShipmentOrder::SHIPMENT_SIGINUP_REJECT_ALL;
        return $this->objData->signupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, [], [], [], $arrRejectSkus);
    }
}