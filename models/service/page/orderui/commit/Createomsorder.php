<?php
/**
 * @name Createomsorder.php
 * @desc Createomsorder.php
 * @author yu.jin03@ele.me
 */

class Service_Page_Orderui_Commit_Createomsorder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * Service_Page_Orderui_Commit_Createomsorder constructor.
     */
    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * @param $arrInput
     * @throws Exception
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function myExecute($arrInput)
    {
        $arrInput['business_form_order_way'] = Orderui_Define_BusinessFormOrder::ORDER_WAY_OBVERSE;
        $arrNwmsResponseList = $this->objDsBusinessFormOrder->createOrder($arrInput);
        Orderui_Wmq_Commit::sendWmqCmd(Orderui_Define_Cmd::CMD_NOTIFY_ISS_OMS_ORDER_CREATE,
            $arrNwmsResponseList, $arrInput['business_form_order_id']);
    }
}