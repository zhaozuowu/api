<?php
/**
 * @name Nwmsorder.php
 * @desc Nwmsorder.php
 * @author yu.jin03@ele.me
 */
class Orderui_Lib_Nwmsorder
{
    /**
     * 格式化沧海异常信息
     * @param $arrResponseList
     * @param $arrBusinessOrderInfo
     * @return mixed
     */
    public static function formatNwmsOrderException($arrResponseList, $arrBusinessOrderInfo)
    {
        foreach ($arrResponseList as $arrResponse) {
            $arrSkuExceptionMap = [];
            foreach ((array)$arrResponse['result']['exceptions'] as $arrException) {
                if (0 == $arrException['sku_id']) {
                    $strOrderException = $arrException['exception_info'];
                } else {
                    $arrSkuExceptionMap[$arrException['sku_id']] = [
                        'exception_info' => $arrException['exception_info'],
                        'exception_time' => $arrException['exception_time'],
                    ];
                }
            }
            if (0 != $arrResponse['result']['error_no']) {
                //存储business_form_order_info
                $strException = empty($strOrderException) ? $arrResponse['result']['error_msg'] : $strOrderException;

                $arrBusinessOrderInfo['business_form_order_exception'] = $strException;
                $arrBusinessOrderInfo['business_form_order_create_status'] = Orderui_Define_Const::OMS_ORDER_DEAL_FAILED;
            } else {
                $arrBusinessOrderInfo['business_form_order_create_status'] = Orderui_Define_Const::OMS_ORDER_DEAL_SUCCESS;
            }
            if (empty($arrResponse['warehouse_id'])) {
                $arrBusinessOrderInfo['business_form_order_exception'] = Orderui_Define_BusinessFormOrder::OMS_WAREHOUSE_DISTRIBUTE_FAILED;
                $arrBusinessOrderInfo['business_form_order_create_status'] = Orderui_Define_Const::OMS_ORDER_DEAL_FAILED;
            }
            $arrBusinessOrderInfo['skus'] = self::formatNwmsOrderSkuException($arrBusinessOrderInfo['skus'], $arrSkuExceptionMap);
        }
        return $arrBusinessOrderInfo;
    }

    /**
     * 格式化sku维度的异常信息
     * @param $arrSkuList
     * @param $arrSkuExceptionMap
     * @return array
     */
    public static function formatNwmsOrderSkuException($arrSkuList, $arrSkuExceptionMap) {
        $arrDealSkuInfo = [];
        foreach ($arrSkuList as $arrSkuInfo) {
            $arrSkuInfoItem = $arrSkuInfo;
            $strSkuException = '';
            $strSkuExceptionTime = 0;
            if (isset($arrSkuExceptionMap[$arrSkuInfo['sku_id']])) {
                $strSkuException = $arrSkuExceptionMap[$arrSkuInfo['sku_id']]['exception_info'];
                $strSkuExceptionTime = $arrSkuExceptionMap[$arrSkuInfo['sku_id']]['exception_time'];
            }
            $arrSkuInfoItem['exception_info'] = $strSkuException;
            $arrSkuInfoItem['exception_time'] = $strSkuExceptionTime;
            $arrDealSkuInfo[] = $arrSkuInfoItem;
        }
        return $arrDealSkuInfo;
    }
}