<?php
/**
 * @name Service_Data_BusinessOrder
 * @desc 业态订单业务逻辑
 * @author hang.song02@ele.me
 */

class Service_Data_BusinessOrder
{
    /**
     * 拆分业态订单
     * @param  array $arrBusinessOrderInfo
     * @return array $arrOrderSysDetailList
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     * @throws Exception
     */
    public function splitBusinessOrder($arrBusinessOrderInfo)
    {
        $intBusinessOrderId = $arrBusinessOrderInfo['business_form_order_id'];

        if (empty($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'business_order_id invalid');
        }
        $arrSkuInfoMap = [];
        foreach ($arrBusinessOrderInfo['skus'] as $arrSkuInfo) {
            $arrSkuInfoMap[$arrSkuInfo['sku_id']] = $arrSkuInfo['order_amount'];
        }
        //check business order has been split
        if (!$this->checkBusinessOrderWhetherSplit($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT, 'business_order_id is already split');
        }

        $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();

        $arrOrderSysList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
            ],
        ];

        $arrOrderSysDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
                'request_info' => $arrBusinessOrderInfo,
            ],
        ];
        //转发
        $res = $this->distributeOrder($arrOrderSysDetailList);
        if (empty($res)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR);
        }
        $arrOrderSysDetailListDb = [];
        $arrOrderSysDetailSkuListDb = [];
        foreach ($res as $re) {
            $strOrderException = '';
            $strOrderExceptionTime = '';
            $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
            foreach ($re['result']['exceptions'] as $arrSkuException) {
                if ($arrSkuException['sku_id'] == 0) {
                    $strOrderException = $arrSkuException['exception_info'];
                    $strOrderExceptionTime = $arrSkuException['exception_time'];
                } else {
                    $arrOrderSysDetailSkuListDb[] = [
                        'order_system_detail_order_id' => $intOrderSystemDetailId,
                        'order_id' => $re['result']['result']['business_form_order_id'],
                        'sku_id' => $arrSkuException['sku_id'] ,
                        'sku_amount' => $arrSkuInfoMap[$arrSkuException['sku_id']],
                        'sku_exception' => $arrSkuException['exception_info'],
                    ];
                }
            }
            foreach ($re['result']['result']['skus'] as $arrSku) {
                $arrSkuItem = [
                    'order_system_detail_order_id' => $intOrderSystemDetailId,
                    'order_id' => $re['result']['result']['business_form_order_id'],
                    'sku_id' => $arrSku['sku_id'],
                    'sku_amount' => $arrSkuInfoMap[$arrSku['sku_id']],
                    'sku_exception' => '',
                ];
                if (!empty($strOrderException)) {
                    $arrSkuItem['sku_exception'] = $strOrderException;
                }
                $arrOrderSysDetailSkuListDb[] = $arrSkuItem;
            }

            $arrOrderSysDetailListDb[] = [
                'order_system_detail_order_id' => $intOrderSystemDetailId,
                'order_system_id' => $re['order_system_id'],
                'order_type' => $re['order_type'],
                'business_form_order_id' => $re['business_form_order_id'],
                'parent_order_id' => $re['order_system_id'],
                'order_id' => $re['result']['result']['business_form_order_id'],
                'order_exception' => $strOrderException,
            ];
        }
        Model_Orm_OrderSystem::getConnection()->transaction(function () use ($arrOrderSysList, $arrOrderSysDetailListDb, $arrOrderSysDetailSkuListDb) {
            Model_Orm_OrderSystem::batchInsert($arrOrderSysList);
            Model_Orm_OrderSystemDetail::batchInsert($arrOrderSysDetailListDb);
            Model_Orm_OrderSystemDetailSku::batchInsert($arrOrderSysDetailSkuListDb);
        });
        return $res;
    }

    /**
     * 验证业态订单是否已经被拆分
     * @param int $intBusinessOrderId
     * @return bool
     */
    public function checkBusinessOrderWhetherSplit($intBusinessOrderId)
    {
        //从DB获取
        $arrOrderInfoInDB = Model_Orm_OrderSystem::getOrderInfoByBusinessOrderId($intBusinessOrderId);
        if (!empty($arrOrderInfoInDB)) {
            return false;
        }
        return true;
    }

    /**
     * 按订单系统类型分发订单
     * @param  array $arrOrderList
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function distributeOrder($arrOrderList)
    {
        $ret = [];
        $objNwmsOrder = new Service_Data_NWmsOrder();
        foreach ($arrOrderList as $arrOrderInfo) {
            $intOrderSysType = $arrOrderInfo['order_system_type'];
            if (Orderui_Define_Const::ORDER_SYS_NWMS == $intOrderSysType) {
                $ret[] = [
                    'result' => $objNwmsOrder->createNWmsOrder($arrOrderInfo['request_info']),
                    'order_system_id' => $arrOrderInfo['order_system_id'],
                    'order_system_type' => $arrOrderInfo['order_system_type'],
                    'business_form_order_id' => $arrOrderInfo['business_form_order_id'],
                    'order_type' => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
                ];
            }
        }
        return $ret;
    }
}