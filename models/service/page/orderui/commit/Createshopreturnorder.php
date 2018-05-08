<?php
/**
 * @name Service_Page_Orderui_Commit_Createshopreturnorder
 * @desc Service_Page_Orderui_Commit_Createshopreturnorder
 * @author huabang.xue@ele.me
 */
class Service_Page_Orderui_Commit_Createshopreturnorder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_Shop
     */
    protected $objDataShop;
    protected $objDataBusinessFormOrder;

    public function __construct()
    {
        $this->objDataShop = new Service_Data_Shop();
        $this->objDataBusinessFormOrder = new Service_Data_BusinessFormOrder();
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
        if (Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_SHOP
            == $arrInput['business_form_order_type']) {
            $arrInput['order_supply_type'] = Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_REJECT;
        }
        $arrInput['business_form_order_way'] = Orderui_Define_BusinessFormOrder::ORDER_WAY_REVERSE;
        //创建逆向业态单
        $res = $this->objDataBusinessFormOrder->createReverseOrder($arrInput);
        Bd_Log::trace(sprintf('method[%s] res %s', __METHOD__, json_encode($res)));
    }
}
