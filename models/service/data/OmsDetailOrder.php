<?php
/**
 * @name Service_Data_OmsDetailOrder
 * @desc oms系统详情订单
 * @author hang.song02@ele.me
 */

class Service_Data_OmsDetailOrder
{
    /**
     * 创建订单详情数据
     * @param integer $intOrderSysId
     * @param integer $intBusinessFormOrderId
     * @param integer $intOrderType
     * @param integer $intParentOrderId
     * @param integer $intOrderId
     * @param integer $intChildrenOrderId
     * @param integer $intOrderSysType
     * @param array   $arrSkuList
     * @param string  $strOrderException
     * @throws Orderui_BusinessError
     * @throws Exception
     */
    public function insertOmsSysDetail($intOrderType, $intParentOrderId,
                                        $intOrderId, $arrSkuList, $intOrderSysType,
                                       $intChildrenOrderId = 0, $strOrderException = '')
    {
        $arrOrderInfo = $this->getOrderInfoByParentOrderId($intParentOrderId, $intOrderType, $intOrderSysType);
        $arrValidateParams = [
            'order_type' => $intOrderType,
            'parent_order_id' => $intParentOrderId,
            'order_id' => $intOrderId,
            'skus' => $arrSkuList,
        ];
        $this->validateEmptyParams($arrValidateParams);
        $this->validateOrderType($intOrderSysType, $intOrderType);
        if (Orderui_Define_Const::ORDER_SYS_NWMS == $intOrderSysType
            && Orderui_Define_Const::NWMS_ORDER_TYPE_STOCK_OUT == $intOrderType
            && empty($intChildrenOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'children order id is invalid');
        }
        $intOrderSysId = $arrOrderInfo['order_system_id'];
        $intBusinessFormOrderId = $arrOrderInfo['business_form_order_id'];
        Model_Orm_OrderSystemDetail::getConnection()->transaction(function () use ($intOrderSysId, $intOrderType,
            $intBusinessFormOrderId, $intOrderId, $intParentOrderId, $intChildrenOrderId, $strOrderException, $arrSkuList){
            $intOrderSysDetailOrderId = Model_Orm_OrderSystemDetail::insertOrderSysDetail($intOrderSysId, $intOrderType,
                $intBusinessFormOrderId, $intOrderId, $intParentOrderId, $intChildrenOrderId, $strOrderException);
            Model_Orm_OrderSystemDetailSku::batchInsertSkuInfo($arrSkuList, $intOrderSysDetailOrderId, $intOrderId);
        });
    }

    /**
     * @param  integer $intParentOrderId
     * @param  integer $intOrderType
     * @param  integer $intOrderSysType
     * @return array   $intOrderSysType
     */
    public function getOrderInfoByParentOrderId($intParentOrderId, $intOrderType, $intOrderSysType)
    {
        if ($intParentOrderType = Orderui_Define_Const::ORDER_PARENT_ORDER_TYPE[$intOrderSysType][$intOrderType]) {
            $arrOrderSysDetailInfo = Model_Orm_OrderSystemDetail::getOrderInfoByOrderId($intParentOrderId, $intParentOrderType);

            $arrOrderInfo = [
                'business_form_order_id' => $arrOrderSysDetailInfo['business_form_order_id'],
                'order_system_id' => $arrOrderSysDetailInfo['order_system_id'],
            ];
        } else {
            $arrOrderSysInfo = Model_Orm_OrderSystem::getOrderInfoByOmsOrderId($intParentOrderId);
            $arrOrderInfo = [
                'business_form_order_id' => $arrOrderSysInfo['business_form_order_id'],
                'order_system_id' => $arrOrderSysInfo['order_system_id'],
            ];
        }
        return $arrOrderInfo;
    }

    /**
     * 验证孔参数
     * @param  array $arrValidateParams
     * @throws Orderui_BusinessError
     */
    public function validateEmptyParams($arrValidateParams)
    {
        foreach ($arrValidateParams as $strKey => $value) {
            if (empty($value)) {
                Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, "{$strKey} param is invalid");
            }
        }
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
}