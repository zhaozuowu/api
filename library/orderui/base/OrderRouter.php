<?php
/**
 * @name OrderRouter.php
 * @desc OrderRouter.php
 * @author yu.jin03@ele.me
 */
abstract class Orderui_Base_OrderRouter
{
    /**
     * 拆分转发入口
     * @param $arrBusinessOrderInfo
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function createOrder($arrBusinessOrderInfo)
    {
        $intBusinessOrderId = $arrBusinessOrderInfo['business_form_order_id'];
        if (empty($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'business_order_id invalid');
        }
        //check business order has been split
        if (!$this->checkBusinessOrderWhetherSplit($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT, 'business_order_id is already split');
        }
        $arrOrderList = $this->splitOrder($arrBusinessOrderInfo, $intBusinessOrderId);
        return $this->distributeOrder($arrOrderList, $arrBusinessOrderInfo['logistics_order_id']);
    }

    /**
     * 校验是否拆分
     * @param $intBusinessOrderId
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
     * 拆分业态单抽象方法
     * @param $arrBusinessOrderInfo
     * @param $intBusinessOrderId
     * @return mixed
     */
    abstract protected function splitOrder($arrBusinessOrderInfo, $intBusinessOrderId);

    /**
     * 转发业态单抽象方法
     * @param $arrOrderList
     * @param $intSourceOrderId
     * @return mixed
     */
    abstract protected function distributeOrder($arrOrderList, $intSourceOrderId);
}