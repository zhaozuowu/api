<?php
/**
 * @name CreateReverseBusinessFormOrder.php
 * @desc CreateReverseBusinessFormOrder.php
 * @author yu.jin03@ele.me
 */
class Service_Page_Business_CreateReverseBusinessFormOrder
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * init object
     */
    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * @param $arrInput
     * @return array
     */
    public function execute($arrInput)
    {
        //悲观锁校验
        $intOrderFlag = $this->objDsBusinessFormOrder->getReverseSourceOrderFlag($arrInput['logistics_order_id']);
        if ($intOrderFlag) {
            return [];
        }
        Orderui_Wmq_Commit::sendWmqCmd(Orderui_Define_Cmd::CMD_CREATE_REVERSE_OMS_ORDER,
                                        $arrInput, $arrInput['logistics_order_id']);
        //设置悲观锁
        $this->objDsBusinessFormOrder->setReverseSourceOrderFlag($arrInput['logistics_order_id']);
        return [];
    }

}