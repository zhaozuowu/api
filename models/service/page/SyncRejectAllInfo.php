<?php
/**
 * @name Service_Page_SyncRejectAllInfo
 * @desc 接收TMS整单拒收信息，转发货架
 * @author wende.chen@ele.me
 */
class Service_Page_SyncRejectAllInfo
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
     * @desc 接收TMS整单拒收信息，转发货架
     * @param array $arrInput
     * @return true
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        $strShipmentOrderId = strval($arrInput['shipment_order_id']);
        $strRejectRemark = strval($arrInput['reject_remark']);
        $strRejectInfo = strval($arrInput['reject_info']);
        return $this->objData->SyncRejectAllInfo($strShipmentOrderId, $strRejectRemark, $strRejectInfo);
    }
}