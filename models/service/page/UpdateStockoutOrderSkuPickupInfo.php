<?php
/**
 * @name Service_Page_UpdateStockoutOrderSkuPickupInfo
 * @desc 接收NWMS出库单拣货信息，通知门店
 * @author wende.chen@ele.me
 */
class Service_Page_UpdateStockoutOrderSkuPickupInfo
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

    /**
     * @desc 接收NWMS出库单拣货信息，通知门店
     * @param $arrInput
     * @return array
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        $strStockoutOrderId = strval($arrInput['stockout_order_id']);
        $arrPickupSkuInfoList = $arrInput['pickup_sku_info_list'];
        return $this->objData->updateStockoutOrderSkuPickupInfo($strStockoutOrderId, $arrPickupSkuInfoList);
    }
}
