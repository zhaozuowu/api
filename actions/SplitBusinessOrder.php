<?php
/**
 * @name Action_SplitBusinessOrder
 * @desc 拆分业态订单
 * @author hang.song02@ele.me
 */

class Action_SplitBusinessOrder extends Orderui_Base_Action
{
    protected $arrInputParams = [
        'business_form_order_id' => 'int|required',
        'logistics_order_id' => 'str|required',
        'business_form_order_type' => 'int|required',
        'shelf_info' => 'json|decode|required',
        'business_form_order_remark' => 'str|max[128]',
        'customer_id' => 'str|required|max[32]',
        'customer_name' => 'str|required|max[32]',
        'customer_contactor' => 'str|required|max[32]',
        'customer_contact' => 'str|max[25]',
        'customer_address' => 'str|required|max[255]',
        'customer_location' => 'str|required|max[128]',
        'customer_location_source' => 'int|required',
        'customer_city_id' => 'int|required',
        'customer_city_name' => 'str|required|max[32]',
        'customer_region_id' => 'int|required',
        'customer_region_name' => 'str|required|max[32]',
        'executor' => 'str|required|max[32]',
        'executor_contact' => 'str|required|max[11]|min[11]',
        'expect_arrive_time' => [
            'validate' => 'json|decode|required',
            'type' => 'map',
            'params' => [
                'start' => 'int|required',
                'end' => 'int|required',
            ],
        ],
        'skus' => [
            'validate' => 'json|decode|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'order_amount' => 'int|required|min[1]',
            ],
        ],
    ];

    /**
     * @var int request method
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * construct
     */
    function myConstruct()
    {
       $this->objPage = new Service_Page_SplitBusinessOrder();
    }

    /**
     * @param  array $data
     * @return array $data
     */
    public function format($data)
    {
        return $data;
    }

}