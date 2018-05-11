<?php
/**
 * @name Createreverseshelforder.php
 * @desc Createreverseshelforder.php
 * @author yu.jin03@ele.me
 */
class Service_Page_Orderui_Commit_Createreverseshelforder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * @param $arrInput
     * @return mixed
     * @throws Exception
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function myExecute($arrInput)
    {
        //根据业态设置补货类型
        if (Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_SHELF
            == $arrInput['business_form_order_type']) {
            $arrInput['supply_type'] = Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_SHELF_RETURN;
        }
        $arrInput['business_form_order_way'] = Orderui_Define_BusinessFormOrder::ORDER_WAY_REVERSE;
        //创建逆向业态单
        $this->objDsBusinessFormOrder->createOrder($arrInput);
    }
}
