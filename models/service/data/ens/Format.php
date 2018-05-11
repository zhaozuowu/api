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

    public static function formatStockinConfirmData($arrInput)
    {
        $arrSkuInfoList = $arrInput['sku_info_list'];
        foreach ($arrSkuInfoList as $arrSkuInfo) {
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
        return Orderui_Struct_WrpcInfo::build([], $arrParams);
    }
}