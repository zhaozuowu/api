<?php
/**
 * @name ShopSignup.php
 * @desc 门店签收
 * @author huabang.xue@ele.me
 */
class Action_Service_ShopSignup extends Orderui_Base_ServiceAction
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
        'stockout_order_id' => 'int|required',
        'skus_event' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'order_amount' => 'int|required',
                'event_type' => 'int|required',
            ],
        ],
        'biz_type' => 'int|default[3]',
    ];

    /*
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_Shop_SignupOrder();
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