<?php
/**
 * @name Service_Data_NWmsOrder
 * @desc Service_Data_NWmsOrder
 * @author hang.song02@ele.me
 */

class Service_Data_NWmsOrder
{
    /**
     * @var Dao_Ral_NWmsOrder
     */
    protected $objDao;

    public function __construct()
    {
        $this->objDao = new Dao_Ral_NWmsOrder();
    }

    /**
     * 创建NWms订单
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function createNWmsOrder($arrBusinessOrderInfo)
    {
        return $this->objDao->createNWmsOrder($arrBusinessOrderInfo);
    }

    /**
     * 处理nwms返回的异常信息
     * @param  array $arrResponseList
     * @param  array $arrBusinessOrderInfo
     * @return array
     */
    public function dealNwmsOrderException($arrResponseList, $arrBusinessOrderInfo)
    {
        foreach ($arrResponseList as $arrResponse) {
            if (0 != $arrResponse['result']['error_no']) {
                //存储business_form_order_info
                $strOrderException = $arrResponse['result']['error_msg'];
                $arrSkuExceptionMap = [];
                foreach ($arrResponse['result']['exceptions'] as $arrException) {
                    if (0 == $arrException['sku_id']) {
                        $strOrderException = $arrException['exception_info'];
                    } else {
                        $arrSkuExceptionMap[$arrException['sku_id']] = [
                            'exception_info' => $arrException['exception_info'],
                            'exception_time' => $arrException['exception_time'],
                        ];
                    }
                }
                $arrBusinessOrderInfo['skus'] = $this->dealNwmsOrderSkuException($arrBusinessOrderInfo['skus'], $arrSkuExceptionMap);
                $arrBusinessOrderInfo['business_form_order_exception'] = $strOrderException;
                $arrBusinessOrderInfo['business_form_order_create_status'] = Orderui_Define_Const::NWMS_ORDER_CREATE_STATUS_FAILED;
            } else {
                $arrBusinessOrderInfo['business_form_order_create_status'] = Orderui_Define_Const::NWMS_ORDER_CREATE_STATUS_SUCCESS;
            }
        }
        return $arrBusinessOrderInfo;
    }

    /**
     * 处理nwms返回的skuy异常信息
     * @param  array $arrSkuList
     * @param  array $arrSkuExceptionMap
     * @return array
     */
    public function dealNwmsOrderSkuException($arrSkuList, $arrSkuExceptionMap)
    {
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
            $arrDealSkuInfo[] = $arrDealSkuInfo;
        }
        return $arrDealSkuInfo;
    }
}