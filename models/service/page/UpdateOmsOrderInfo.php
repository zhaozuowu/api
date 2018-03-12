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
        //验证订单来源类型合法性
        $this->objDataOrderSys->validateOrderSysType($arrInput['order_sys_type']);
        $this->objDataOrderSysDetail->insertOmsSysDetail($arrInput['order_type'], $arrInput['parent_order_id'],
            $arrInput['order_id'], $arrInput['skus'], $arrInput['order_sys_type'],
            $arrInput['children_order_id'], $arrInput['order_exception']
        );
    }
}