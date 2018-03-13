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
     */
    public function splitBusinessOrder($arrBusinessOrderInfo)
    {
        $intBusinessOrderId = $arrBusinessOrderInfo['business_form_order_id'];

        if (empty($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'business_order_id invalid');
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

        $arrBusinessOrderInfo['logistics_order_id'] = Nscm_Define_OrderPrefix::OMS . $intOrderSystemId;
        $arrOrderSysDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'request_info' => $arrBusinessOrderInfo,
            ],
        ];
        //转发
        $res = $this->distributeOrder($arrOrderSysDetailList);
        if (empty($res)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR);
        }
        //存储数据
        Model_Orm_OrderSystem::batchInsert($arrOrderSysList);
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
                ];
            }
        }
        return $ret;
    }
}