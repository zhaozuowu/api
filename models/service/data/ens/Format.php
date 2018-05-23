<?php
/**
 * @name Service_Data_Ens_Format
 * @desc Service_Data_Ens_Format
 * @author bochao.lv@ele.me
*/

class Service_Data_Ens_Format
{
    /**
     * default format
     * @param array $arrInput
     * @return array
     */
    public static function defaultFormat($arrInput)
    {
        return $arrInput;
    }

    /**
     * default wrpc format
     * @param $arrInput
     * @return mixed
     */
    public static function defaultWrpcFormat($arrInput)
    {
        return Orderui_Struct_WrpcInfo::build([], $arrInput);
    }

    /**
     * format nwms finish order
     * @param $arrInput
     * @return array
     */
    public static function formatNwmsFinishOrder($arrInput)
    {
        return [
            'stockout_order_id' => $arrInput['stockout_order_id'],
            'signup_status' => $arrInput['signup_status'],
            'signup_skus' => $arrInput['signup_skus'],
        ];
    }

    /**
     * @param $arrInput
     * @return Orderui_Struct_WrpcInfo
     */
    public static function deliveryOrderFormatNwms($arrInput)
    {
        return Orderui_Struct_WrpcInfo::build([],
            ['stockout_order_id' => intval($arrInput['stockout_order_id'])]);
    }

    /**
     * @param $arrInput
     * @return Orderui_Struct_WrpcInfo
     */
    public static function batchPickingAmount($arrInput)
    {
        return Orderui_Struct_WrpcInfo::build([],[
            'receiptProductsInfo' => $arrInput['batch_pickup_info'],
        ]);
    }

    public static function getWarehouseInfo($intRegionId)
    {
        $objDaoRalWarehouse = new Dao_Ral_Warehouse();
        $arrRet = $objDaoRalWarehouse->getWarehouseListByDistrictId($intRegionId);
        if (empty($arrRet)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_GET_WAREHOUSE_INFO_FAILED);
        }
        $arrWarehouseInfo['warehouse_id'] = $arrRet[0]['warehouse_id'];
        $arrWarehouseInfo['warehouse_name'] = $arrRet[0]['warehouse_name'];
        $arrWarehouseInfo['warehouse_location'] = Orderui_Util_Utility::transferBMapToAMap($arrRet[0]['location']);
        return $arrWarehouseInfo;
    }

    public static function formatStockinConfirmData($arrInput)
    {
        $intShipmentOrderId = $arrInput['shipment_order_id'];
        $arrShipmentOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intShipmentOrderId,
            Nscm_Define_OmsOrder::TMS_ORDER_TYPE_SHIPMENT);
        $intBusinessFormOrderId = intval($arrShipmentOrder['business_form_order_id']);
        $objBusinessFormOrder = Model_Orm_BusinessFormOrder::getBusinessFormOrderByBusinessOrderId($intBusinessFormOrderId);
        //非撤点单不发确认入库事件
        if ($objBusinessFormOrder['supply_type'] != Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_RETREAT) {
            return true;
        }
        $strRegionId = json_decode($objBusinessFormOrder['business_form_ext'], true)['region_id'];
        if (empty($strRegionId)) {
            Bd_Log::warning(sprintf('this_business_form_order_has_not_region_id, business_form_order_id:[%s]', $intBusinessFormOrderId));
        }
        $arrWarehouseInfo = self::getWarehouseInfo(intval($strRegionId));
        $arrSkuInfoList = $arrInput['sku_info_list'];
        $arrSkus = [];
        foreach ((array)$arrSkuInfoList as $arrSkuInfo) {
            $arrSkus[] = [
                'id' => intval($arrSkuInfo['sku_id']),
                'count' => $arrSkuInfo['sku_amount'],
            ];
        }
        $arrSignRequest = [
            'shipmentId' => $arrInput['shipment_order_id'],
            'bizType'    => $arrInput['biz_type'],
            'skus'       => $arrSkus,
        ];
        $arrParams = [
            'shipmentId' => $arrInput['shipment_order_id'],
            'request'    => $arrSignRequest,
            'user'       => (object)[],
        ];
        $strRoutingKey = sprintf("loc=%s", $arrWarehouseInfo['warehouse_location']);
        return Orderui_Struct_WrpcInfo::build(['routing-key' => $strRoutingKey, 'shardid' => $arrInput['shipment_order_id'] % 100], $arrParams);
    }
}