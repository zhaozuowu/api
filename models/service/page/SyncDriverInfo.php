<?php
/**
 * @name Service_Page_SyncDriverInfo
 * @desc 接收TMS司机信息，转发到货架
 * @author wende.chen@ele.me
 */
class Service_Page_SyncDriverInfo
{
    /**
     * @var objData
     */
    protected $objData;

    /**
     * init object
     */
    public function __construct()
    {
        $this->objData = new Service_Data_Shelf();
    }

    /**
     * @desc 同步转发司机信息到货架
     * @param array $arrInput
     * @return true
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        $strShipmentOrderId = strval($arrInput['shipment_order_id']);
        $intDriverSex = intval($arrInput['driver_info']['sex']);
        $strDriverName = $arrInput['driver_info'][0]['name'];
        $strDriverMobile = $arrInput['driver_info'][0]['contact_phone'];
        $strDriverId = $arrInput['driver_info'][0]['id'];

        return $this->objData->SyncDriverInfo($strShipmentOrderId, $intDriverSex, $strDriverId, $strDriverName, $strDriverMobile);
    }
}