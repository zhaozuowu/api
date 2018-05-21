<?php
class Action_Service_RecallShelf extends Orderui_Base_ServiceAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'logistics_order_id' => 'str|required',
        'business_form_order_type' => 'int|required',
        'business_form_order_remark' => 'str|required|len[255]',
        'shelf_sku_list' => 'arr|required',
        'customer_info' => 'arr|required',
        'expect_arrive_time' => 'arr|required',
    ];

    /**
     * method post
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * init object
     */
    public function myConstruct() {
        $this->objPage = new Service_Page_Business_RecallShelf();
    }

    /**
     * @param $arrRet
     * @return mixed
     */
    public function format($arrRet)
    {
        return $arrRet;
    }
}