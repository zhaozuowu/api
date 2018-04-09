<?php

/*
 * @name Action_Service_SignupShipmentOrder
 * @desc 对运单进行签收
 * @author huabang.xue@ele.me
 */
class Action_Service_SignupShipmentOrder extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'shipment_order_id' => 'str|required',
        'signup_status' => 'int|required|min[1]|max[3]',
        'signup_skus' => 'arr|required',
        'offshelf_skus' => 'arr',
        'adjust_skus' => 'arr',
    ];

    /*
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_SignupShipmentOrder();
    }

    /*
     * @desc format result
     * @param array $data
     * @return array
     */
    public function format($data)
    {
        return $data;
    }
}