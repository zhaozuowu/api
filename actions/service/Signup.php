<?php
/**
 * @name Signup.php
 * @desc Signup.php
 * @author yu.jin03@ele.me
 */
class Action_Service_Signup extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * @var array
     */
    protected $arrInputParams = [
        'logistics_order_id' => 'str|required',
        'sku_events' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'order_amount' => 'int|required',
                'event_type' => 'int|required',
            ],
        ],
        'user' => 'arr|required',
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