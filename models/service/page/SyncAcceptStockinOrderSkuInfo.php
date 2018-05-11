<?php
/**
 * @name Service_Page_SyncAcceptStockinOrderSkuInfo
 * @desc 接收nwms揽收信息，转发货架
 * @author wende.chen@ele.me
 */
class Service_Page_SyncAcceptStockinOrderSkuInfo
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
     * @desc 接收nwms揽收信息，转发货架
     * @param array $arrInput
     * @return true
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        $strLogisticOrderId = $arrInput['logistic_order_id'];
        $strShipmentOrderId = $arrInput['shipment_order_id'];

        /** 此处为兼容之前货架被TMS调用是传入司机信息的接口而写，传入司机信息为空 **/
        $arrDriverInfo = [
            'driver_id' => '',
            'driver_name' => '',
            'driver_mobile' => '',
        ];

        $arrSkuInfo = $arrInput['sku_info'];

        return $this->objData->SyncAcceptStockinOrderSkuInfo(
            $strLogisticOrderId,
            $strShipmentOrderId,
            $arrDriverInfo,
            $arrSkuInfo);
    }
}