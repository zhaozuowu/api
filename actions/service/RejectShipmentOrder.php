<?php
/*
 * @name Action_Service_RejectShipmentOrder
 * @desc 对运单进行拒收
 * @author yu.jin03@ele.me
 */
class Action_Service_RejectShipmentOrder extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'shipment_order_id' => 'str|required',
        'reject_skus' => 'arr|required',
    ];

    /*
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_RejectShipmentOrder();
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