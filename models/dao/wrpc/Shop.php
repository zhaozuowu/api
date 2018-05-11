<?php
/**
 * @name Dao_Wrpc_Shop
 * @desc wrpc方式调用shop
 * @author wende.chen@ele.me
 */

class Dao_Wrpc_Shop
{
    /**
     * wrcp service
     * @var Shop
     */
    private $objWrpcService;

    /**
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_SHOP,
            Orderui_Define_Wrpc::NAMESPACE_SHOP,
            Orderui_Define_Wrpc::SERVICE_NAME_SHOP);
    }


    /**
     * 接收出库单拣货信息，通知门店
     * @param $intNwmsStockoutOrderId
     * @param  array $arrPickupSkuInfoList
     * @return array
     * @throws Orderui_BusinessError
     */
    public function updateStockoutOrderSkuPickupInfo($intNwmsStockoutOrderId, $arrPickupSkuInfoList)
    {
        $strRoutingKey = sprintf("stockout_order_id=%s", $intNwmsStockoutOrderId);
        $arrNwmsPickupSkuInfoList = [];
        foreach ($arrPickupSkuInfoList as $pickupSkuInfo) {
            $arrPickupSkuInfo = [];
            $arrPickupSkuInfo['sku_id'] = $pickupSkuInfo['sku_id'];
            $arrPickupSkuInfo['wms_number'] = $pickupSkuInfo['sku_amount'];
            $arrNwmsPickupSkuInfoList[] = $arrPickupSkuInfo;
        }

        $arrNwmsStockoutOrderPickupInfo['wms_receipts_id'] = $intNwmsStockoutOrderId;
        $arrNwmsStockoutOrderPickupInfo['receipts_detail'] = json_encode($arrNwmsPickupSkuInfoList);
        $arrRet = $this->objWrpcService->wmsGoodsNumber($arrNwmsStockoutOrderPickupInfo);
        Bd_Log::trace(sprintf("method[%s] call shop service update stockout order sku amount [%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_UPDATE_SHOP_STOCKOUT_SKU_PICKUP_AMOUNT_FAIL);
        }
        return $arrRet;
    }
}