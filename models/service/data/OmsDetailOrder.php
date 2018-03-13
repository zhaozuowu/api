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
    public function addOmsSysDetail($intOrderType, $intParentOrderId,
                                        $intOrderId, $arrSkuList, $intOrderSysType, $intBusinessFormOrderId,
                                       $intOrderSysId, $intChildrenOrderId = 0, $strOrderException = '')
    {
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
        //验证订单是否已经存在
        if ($this->validateOrderIsExisted($intOrderId, $intOrderSysId, $intOrderType)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::ORDER_SYS_DETAIL_IS_EXITED);
        }
        Model_Orm_OrderSystemDetail::getConnection()->transaction(function () use ($intOrderSysId, $intOrderType,
            $intBusinessFormOrderId, $intOrderId, $intParentOrderId, $intChildrenOrderId, $strOrderException, $arrSkuList){
            $intOrderSysDetailOrderId = Model_Orm_OrderSystemDetail::insertOrderSysDetail($intOrderSysId, $intOrderType,
                $intBusinessFormOrderId, $intOrderId, $intParentOrderId, $intChildrenOrderId, $strOrderException);
            Model_Orm_OrderSystemDetailSku::batchInsertSkuInfo($arrSkuList, $intOrderSysDetailOrderId, $intOrderId);
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