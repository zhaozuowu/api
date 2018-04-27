<?php
class Action_Service_ReCallShelf extends Orderui_Base_ServiceAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'logistics_order_id' => 'str|required',
        'business_form_order_type' => 'int|required',
        'shelf_info' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'device_type' => 'int|required',
                'device_amount' => 'int|required',
                'device_check_status' => 'str|required',
            ],
        ],
        'customer_info' => [
            'validate' => 'arr|required',
            'type' => 'map',
            'params' => [
                'id' => 'str|required',
                'name' => 'str|required',
                'contactor' => 'str|required',
                'contact' => 'str|required',
                'address' => 'str|required',
                'location' => 'str|required',
                'location_source' => 'int|required',
                'city_id' => 'int|required',
                'city_name' => 'str|required',
                'region_id' => 'int|required',
                'region_name' => 'str|required',
                'executor' => 'str|required',
                'executor_contact' => 'str|required',
            ],
        ],
        'skus' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'display_floor' => 'int|required',
                'return_amount' => 'int|required',
            ],
        ],
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
        $this->objPage = new Service_Page_Business_CreateReverseBusinessFormOrder();
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