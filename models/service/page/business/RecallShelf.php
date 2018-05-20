<?php

class Service_Page_Business_RecallShelf
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
     * @throws Exception
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function execute($arrInput)
    {
        //悲观锁校验
        $arrInput = $this->objDsBusinessFormOrder->assembleExtraRecallShelfData($arrInput);
        $intOrderFlag = $this->objDsBusinessFormOrder->getReverseSourceOrderFlag($arrInput['logistics_order_id']);
        if ($intOrderFlag) {
            return 1;
        }
        $arrInput['business_form_order_id'] = Orderui_Util_Utility::generateBusinessFormOrderId();
        Orderui_Wmq_Commit::sendWmqCmd(Orderui_Define_Cmd::CMD_CREATE_REVERSE_SHELF_ORDER,
                                        $arrInput, $arrInput['logistics_order_id']);
        //设置悲观锁
        $this->objDsBusinessFormOrder->setReverseSourceOrderFlag($arrInput['logistics_order_id']);
        return 1;
    }

}