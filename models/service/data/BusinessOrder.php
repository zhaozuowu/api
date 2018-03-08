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
     * @param int $intBusinessOrderId
     * @return array
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function splitBusinessOrder($intBusinessOrderId)
    {
        if (empty($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'business_order_id invalid');
        }
        //获取business_order_info
        $arrBusinessOrderInfo = $this->getBusinessOrderInfoFromRedis($intBusinessOrderId);

        //check business order has been split
        if (!$this->checkBusinessOrderWhetherSplit($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT, 'business_order_id is already split');
        }

        $intOrderSystemId = Orderui_Util_Util::generateOmsOrderCode();

        $arrOrderList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
            ],
        ];

        $arrOrderDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'skus' => $arrBusinessOrderInfo['skus'],
            ],
        ];

        //存储数据？
        Model_Orm_OrderSystem::batchInsert($arrOrderList);
        return $arrOrderDetailList;
    }

    /**
     * 验证业态订单是否已经被拆分
     * @param int $intBusinessOrderId
     * @return bool
     */
    public function checkBusinessOrderWhetherSplit($intBusinessOrderId)
    {
        //从redis获取
        $arrOrderInfoInRedis = $this->getBusinessOrderInfoFromRedis($intBusinessOrderId);
        if (empty($arrOrderInfoInRedis)) {
            return false;
        }
        //从DB获取
        $arrOrderInfoInDB = Model_Orm_OrderSystem::getOrderInfoByBusinessOrderId($intBusinessOrderId);
        if (!empty($arrOrderInfoInDB)) {
            return false;
        }
        return true;
    }

    /**
     * get business order info from redis by business_order_id
     * @param  int   $intBusinessOrderId
     * @return array $businessOrderInfo
     */
    public function getBusinessOrderInfoFromRedis($intBusinessOrderId)
    {
        $objRedis = new Dao_Redis_BusinessOrder();
        $strRedisKey = strval($intBusinessOrderId);
        return $objRedis->getOrderInfo($strRedisKey);
    }

    /**
     * set business order info into redis
     * @param  array  $arrBusinessOrderInfo
     * @return string $strKey
     */
    public function setBusinessOrderInfoIntoRedis($arrBusinessOrderInfo)
    {
        $objRedis = new Dao_Redis_BusinessOrder();
        return $objRedis->setOrderInfo($arrBusinessOrderInfo);
    }
}