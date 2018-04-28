<?php
/**
 * @name Action_Service_CreateShopReturnOrder
 * @desc 门店创建退货单
 * @author huabang.xue@ele.me
 */
class Action_Service_CreateShopReturnOrder extends Orderui_Base_ServiceAction
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
        'logistics_order_id' => 'int|required',
        'business_form_order_type' => 'int|required',
        'business_form_order_remark' => 'str|len[256]',
        'skus' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'order_amount' => 'int|required',
            ],
        ],
        'customer_id' => 'str|required|len[32]',
        'customer_name' => 'str|required|len[128]',
        'customer_contactor' => 'str|required|len[32]',
        'customer_contact' => 'str|len[25]',
        'customer_address' => 'str|required|len[255]',
        'customer_location' => 'str|required|len[128]',
        'customer_location_source' => 'int|required',
        'customer_city_id' => 'int|required',
        'customer_city_name' => 'str|required|len[32]',
        'customer_region_id' => 'int|required|min[1]',
        'customer_region_name' => 'str|required|len[32]',
        'executor' => 'str|required|len[32]',
        'executor_contact' => 'str|required|len[11]|min[11]',
    ];

    /*
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_Shop_CreateShopReturnOrder();
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