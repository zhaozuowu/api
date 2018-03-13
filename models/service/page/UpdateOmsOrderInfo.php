<?php
/**
 * @name Service_Page_UpdateOmsOrderInfo
 * @desc 增量更新Oms子订单信息
 * @author hang.song02@ele.me
 */

class Service_Page_UpdateOmsOrderInfo
{
    /**
     * @var Service_Data_OmsOrder
     */
    protected $objDataOrderSysDetail;

    /**
     * @var Service_Data_OmsOrder
     */
    protected $objDataOrderSys;

    /**
     * Service_Page_UpdateOmsOrderInfo constructor.
     */
    public function __construct()
    {
        $this->objDataOrderSysDetail = new Service_Data_OmsDetailOrder();
        $this->objDataOrderSys = new Service_Data_OmsOrder();
    }

    /**
     * @param array $arrInput
     * @throws Orderui_BusinessError
     * @throws Exception
     */
    public function execute($arrInput)
    {
        $arrOrderSysInfo = $this->objDataOrderSys->getOrderInfoByOrderIdAndType($arrInput['parent_order_id']);
        $this->objDataOrderSysDetail->addOmsSysDetail($arrInput['order_type'], $arrInput['parent_order_id'],
            $arrInput['order_id'], $arrInput['skus'], $arrOrderSysInfo['order_system_type'],
            $arrOrderSysInfo['business_form_order_id'], $arrOrderSysInfo['order_system_id'],
            $arrInput['children_order_id'], $arrInput['order_exception']
        );
    }
}