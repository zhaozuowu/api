<?php
/**
 * @name Service_Data_OmsOrder
 * @desc Oms订单业务
 * @author hang.song02@ele.me
 */

class Service_Data_OmsOrder
{
    /**
     * 更新oms订单信息
     * @param  int $intOmsOrderId
     * @param  int $intStockOutOrderId
     * @param  int $intShipmentOrderId
     * @param  int $intOrderSysType
     * @throws Orderui_BusinessError
     */
    public function UpdateOmsOrderInfo($intOmsOrderId, $intStockOutOrderId, $intShipmentOrderId, $intOrderSysType)
    {
        $arrValidateParams = [
            'oms_order_id' => $intOmsOrderId,
            'stockout_oder_id' => $intStockOutOrderId,
            'shipment_order_id' => $intShipmentOrderId,
        ];
        $this->validateEmptyParams($arrValidateParams);
        if (!$this->checkOmsOrderIsExisted($intOmsOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_ORDER_IS_NOT_EXITED);
        }

        $arrFields = [

        ];
        $arrCondition = [
            'order_system_id' => $intOmsOrderId,
            'order_system_type' => $intOrderSysType,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];

        Model_Orm_OrderSystem::updateAll($arrFields, $arrCondition);
    }

    /**
     * 通过order system id 获取订单信息
     * @param  integer $intOrderSysId
     * @return array
     */
    public function getOrderInfoByOrderSysId($intOrderSysId)
    {
         return Model_Orm_OrderSystem::getOrderInfoByOmsOrderId($intOrderSysId);
    }
    /**
     * @param  integer $intOmsOrderId
     * @return boolean
     */
    public function checkOmsOrderIsExisted($intOmsOrderId)
    {
        $arrOrderInfo = Model_Orm_OrderSystem::getOrderInfoByOmsOrderId($intOmsOrderId);
        if (empty($arrOrderInfo)) {
            return false;
        }
        return true;
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
     * 通过下游系统订单号和订单类型获取订单信息
     * @param  integer $intOrderId
     * @param  integer $intOrderType
     * @return array
     */
    public function getOrderInfoByOrderIdAndType($intOrderId, $intOrderType)
    {
        return Model_Orm_OrderSystem::getOrderInfoByOrderIdAndType($intOrderId, $intOrderType);
    }
}