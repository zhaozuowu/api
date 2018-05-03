<?php
/**
 * @name Ordersystemdetail.php
 * @desc Ordersystemdetail.php
 * @author yu.jin03@ele.me
 */

class Orderui_Lib_Ordersystemdetail
{
    /**
     * 格式化子单数据
     * @param $arrSkuInfoList
     * @param $arrResponseList
     * @return array
     * @throws Wm_Error
     */
    public static function formatOrderSystemDetailInfo($arrResponseList, $arrSkuInfoList)
    {
        $arrOrderSysDetailListDb = [];
        $arrOrderSysDetailSkuListDb = [];
        $arrSkuInfoMap = [];
        foreach ($arrSkuInfoList as $arrSkuInfo) {
            $arrSkuInfoMap[$arrSkuInfo['sku_id']] = $arrSkuInfo['order_amount'];
        }
        foreach ($arrResponseList as $re) {
            $strOrderException = '';
            $strOrderExceptionTime = '';
            $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
            foreach ($re['result']['exceptions'] as $arrSkuException) {
                if ($arrSkuException['sku_id'] == 0) {
                    $strOrderException = $arrSkuException['exception_info'];
                    $strOrderExceptionTime = $arrSkuException['exception_time'];
                } else {
                    $arrOrderSysDetailSkuListDb[] = [
                        'order_system_detail_id' => $intOrderSystemDetailId,
                        'order_id' => $re['result']['result']['business_form_order_id'],
                        'sku_id' => $arrSkuException['sku_id'] ,
                        'sku_amount' => $arrSkuInfoMap[$arrSkuException['sku_id']],
                        'sku_exception' => $arrSkuException['exception_info'],
                    ];
                }
            }
            if (!empty($re['result']['result']['skus'])) {
                foreach ($re['result']['result']['skus'] as $arrSku) {
                    //分配数量为0-异常信息中已存在
                    if (0 == $arrSku['distribute_amount']) {
                        continue;
                    }
                    $arrSkuItem = [
                        'order_system_detail_id' => $intOrderSystemDetailId,
                        'order_id' => $re['result']['result']['business_form_order_id'],
                        'sku_id' => $arrSku['sku_id'],
                        'sku_amount' => $arrSkuInfoMap[$arrSku['sku_id']],
                        'sku_exception' => '',
                    ];
                    $arrOrderSysDetailSkuListDb[] = $arrSkuItem;
                }
            }

            $arrOrderSysDetailListDb[] = [
                'order_system_detail_id' => $intOrderSystemDetailId,
                'order_system_id' => $re['order_system_id'],
                'order_type' => $re['order_type'],
                'business_form_order_id' => $re['business_form_order_id'],
                'parent_order_id' => $re['order_system_id'],
                'order_id' => $re['result']['result']['business_form_order_id'],
                'order_exception' => $strOrderException,
            ];
        }

        return [
            'detail_list' => $arrOrderSysDetailListDb,
            'sku_list' => $arrOrderSysDetailSkuListDb,
        ];
    }

    /**
     * 格式化子单数据
     * @param $arrSkuInfoList
     * @param $arrResponseList
     * @return array
     * @throws Wm_Error
     */
    public static function formatOrderSystemDetailInfoForReverse($arrResponseList, $arrSkuInfoList)
    {
        $arrOrderSysDetailListDb = [];
        $arrOrderSysDetailSkuListDb = [];
        $arrSkuInfoMap = [];
        foreach ((array)$arrSkuInfoList as $arrSkuInfo) {
            $arrSkuInfoMap[$arrSkuInfo['sku_id']] = $arrSkuInfo['order_amount'];
        }
        foreach ($arrResponseList as $re) {
            $strOrderException = '';
            $strOrderExceptionTime = '';
            $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
            foreach ((array)$re['result']['exceptions'] as $arrSkuException) {
                if ($arrSkuException['sku_id'] == 0) {
                    $strOrderException = $arrSkuException['exception_info'];
                    $strOrderExceptionTime = $arrSkuException['exception_time'];
                } else {
                    $arrOrderSysDetailSkuListDb[] = [
                        'order_system_detail_id' => $intOrderSystemDetailId,
                        'order_id' => intval($re['result']['stockin_order_id']),
                        'sku_id' => $arrSkuException['sku_id'] ,
                        'sku_amount' => $arrSkuInfoMap[$arrSkuException['sku_id']],
                        'sku_exception' => $arrSkuException['exception_info'],
                    ];
                }
            }
            if (!empty($re['result']['skus'])) {
                foreach ($re['result']['skus'] as $arrSku) {
                    $arrSkuItem = [
                        'order_system_detail_id' => $intOrderSystemDetailId,
                        'order_id' => intval($re['result']['stockin_order_id']),
                        'sku_id' => $arrSku['sku_id'],
                        'sku_amount' => $arrSkuInfoMap[$arrSku['sku_id']],
                        'sku_exception' => '',
                    ];
                    $arrOrderSysDetailSkuListDb[] = $arrSkuItem;
                }
            }

            $arrOrderSysDetailListDb[] = [
                'order_system_detail_id' => $intOrderSystemDetailId,
                'order_system_id' => $re['order_system_id'],
                'order_type' => $re['order_type'],
                'business_form_order_id' => $re['business_form_order_id'],
                'parent_order_id' => $re['order_system_id'],
                'order_id' => intval($re['result']['stockin_order_id']),
                'order_exception' => $strOrderException,
            ];
        }

        return [
            'detail_list' => $arrOrderSysDetailListDb,
            'sku_list' => $arrOrderSysDetailSkuListDb,
        ];
    }
}