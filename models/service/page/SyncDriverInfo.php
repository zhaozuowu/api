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
        $strLogisticOrderId = strval($arrInput['logistic_order_id']);
        $strDriverId = $arrInput['driver_info']['driver_id'];
        $strDriverName = $arrInput['driver_info']['driver_name'];
        $strDriverMobile = $arrInput['driver_info']['driver_mobile'];
        return $this->objData->SyncDriverInfo($strLogisticOrderId, $strDriverId, $strDriverName, $strDriverMobile);
    }
}