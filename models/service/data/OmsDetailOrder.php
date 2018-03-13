<?php
/**
 * @name Service_Data_OmsDetailOrder
 * @desc oms系统详情订单
 * @author hang.song02@ele.me
 */

class Service_Data_OmsDetailOrder
{
    /**
     * 增量更新子单下游订单详情
     * @param  array $arrOrderDetailList
     * @param  array $arrOrderSkuList
     * @throws Orderui_BusinessError
     * @throws Exception
     */
    public function addOrderSysDetail($arrOrderDetailList, $arrOrderSkuList)
    {
        if (empty($arrOrderDetailList) || empty($arrOrderSkuList)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrOrderDetailListDb = [];
        //去除$arrOrderDetailList中的sku
        foreach ($arrOrderDetailList as  $arrOrderDetailInfo) {
            $arrOrderDetailListDb[] = [
                'order_system_detail_order_id' => $arrOrderDetailInfo['order_system_detail_order_id'],
                'order_system_id' => $arrOrderDetailInfo['order_system_id'],
                'order_type' => $arrOrderDetailInfo['order_type'],
                'business_form_order_id' => $arrOrderDetailInfo['business_form_order_id'],
                'parent_order_id' => $arrOrderDetailInfo['parent_order_id'],
                'order_id' => $arrOrderDetailInfo['order_id'],
                'order_exception' => $arrOrderDetailInfo['order_exception'],
            ];
        }
        Model_Orm_OrderSystemDetail::getConnection()->transaction(function () use ($arrOrderDetailListDb, $arrOrderSkuList){
            Model_Orm_OrderSystemDetail::batchInsert($arrOrderDetailListDb);
            Model_Orm_OrderSystemDetailSku::batchInsert($arrOrderSkuList);
        });
    }

    /**
     * 验证订单是否已经存在
     * @param  integer $intOrderId
     * @param  integer $intOrderSysId
     * @param  integer $intOrderType
     * @return bool
     */
    public function validateOrderIsExisted($intOrderId, $intOrderSysId, $intOrderType)
    {
        $arrOrderSysInfo = Model_Orm_OrderSystemDetail::getOrderInfo($intOrderId, $intOrderType, $intOrderSysId);
        if (!empty($arrOrderSysInfo)) {
            return true;
        }
        return false;
    }

    /**
     * 验证oms订单详情类型是否合法
     * @param  integer $intOrderSysType
     * @param  integer $intOrderType
     * @throws Orderui_BusinessError
     */
    public function validateOrderType($intOrderSysType, $intOrderType)
    {
        if (Orderui_Define_Const::ORDER_SYS_NWMS == $intOrderSysType) {
            if (!in_array($intOrderType, Orderui_Define_Const::NWMS_ORDER_TYPE)) {
                Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_ORDER_DETAIL_TYPE_INVALID);
            }
        }
    }

    /**
     * 组装订单存储数据
     * @param  array $arrOrderInfoList
     * @return array
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function assembleOmsSysDetailInfo($arrOrderInfoList)
    {
        if (empty($arrOrderInfoList)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrOrderInfoListData = [];
        foreach ($arrOrderInfoList as $intKey => $arrOrderInfo) {
            $arrOrderInfoItem = $arrOrderInfo;
            if ($intKey <= $arrOrderInfo['parent_key']) {
                Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_EVENT_CALLBACK_INVALID);
            }
            if (Orderui_Define_Const::OMS_EVENT_INVALID_PARENT_KEY != $arrOrderInfo['parent_key']) {
                $arrParentOrderInfo = $arrOrderInfoListData[$arrOrderInfo['parent_key']];
                if ($arrParentOrderInfo['order_id'] != $arrOrderInfo['parent_order_id']) {
                    Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_EVENT_CALLBACK_PARENT_KEY_INVALID);
                }
                $intOrderSysId = $arrParentOrderInfo['order_system_id'];
                $intBusinessFormOrderId = $arrParentOrderInfo['business_form_order_id'];
            } else {
                //通过表获取订单信息
                $intParentOrderType = Orderui_Define_Const::ORDER_PARENT_ORDER_TYPE[$arrOrderInfo['order_type']];

                $arrOrderInfoInDB = $this->getOrderInfoByOrderIdAndType($arrOrderInfo['parent_order_id'], $intParentOrderType);
                if (empty($arrOrderInfoInDB)) {
                    Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_ORDER_IS_NOT_EXITED);
                }
                $intOrderSysId = $arrOrderInfoInDB['order_system_id'];
                $intBusinessFormOrderId = $arrOrderInfoInDB['business_form_order_id'];
            }

            if ($this->validateOrderIsExisted($arrOrderInfo['order_id'], $intOrderSysId, $arrOrderInfo['order_type'])) {
                continue;
            }
            unset($arrOrderInfoItem['parent_key']);
            $arrOrderInfoItem['order_system_id'] = $intOrderSysId;
            $arrOrderInfoItem['business_form_order_id'] = $intBusinessFormOrderId;
            $arrOrderInfoItem['order_system_detail_order_id'] = Orderui_Util_Utility::generateOmsOrderCode();
            $arrOrderInfoListData[$intKey] = $arrOrderInfoItem;
        }
        if (empty($arrOrderInfoListData)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::ORDER_SYS_DETAIL_IS_EXITED);
        }
        return $arrOrderInfoListData;
    }

    /**
     * 组装子单sku详情信息
     * @param  array $arrOrderInfoList
     * @return array
     * @throws Orderui_BusinessError
     */
    public function assembleOmsSysDetailSkuInfo($arrOrderInfoList)
    {
        if (empty($arrOrderInfoList)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrSkuListDb = [];
        foreach ($arrOrderInfoList as $arrOrderInfo) {
            $arrSkuList = $arrOrderInfo['skus'];
            foreach ($arrSkuList as  $arrSkuInfo) {
                $arrSkuListDbItem = [
                    'order_system_detail_order_id' => $arrOrderInfo['order_system_detail_order_id'],
                    'order_id' => $arrOrderInfo['order_id'],
                    'sku_id' => $arrSkuInfo['sku_id'],
                    'sku_amount' => $arrSkuInfo['sku_amount'],
                    'sku_ext' => $arrSkuInfo['sku_ext'],
                    'sku_exception' => $arrSkuInfo['sku_exception'],
                ];
                if (empty($arrSkuInfo['sku_exception'])) {
                    $arrSkuListDbItem['sku_exception'] = $arrOrderInfo['order_exception'];
                }
                $arrSkuListDb[] = $arrSkuListDbItem;
            }
        }
        return $arrSkuListDb;
    }

    /**
     * 通过下游系统订单号和订单类型获取订单信息
     * @param  integer $intOrderId
     * @param  integer $intOrderType
     * @return array
     */
    public function getOrderInfoByOrderIdAndType($intOrderId, $intOrderType)
    {
        return Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intOrderId, $intOrderType);
    }
}