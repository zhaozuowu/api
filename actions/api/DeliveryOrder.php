<?php
/**
 * @name Action_DeliveryOrder
 * @desc 出库单揽收时接收产效期传给货架
 * @author hang.song02@ele.me
 */

class Action_DeliveryOrder extends Orderui_Base_ApiAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'stockout_order_id' => 'int|required',
        'sku_info' => [
            'validate' => 'json|decode|required',
            'type' => 'array',
            'params' => [
                'sku_id'             => 'int|required',
                'sku_extra_info'     =>[
                    'validate' => 'json|decode|required',
                    'type' => 'array',
                    'params' => [
                        'expire_time'             => 'int|required',
                        'product_time'     =>  'int|required',
            ],
                ],
            ],
        ],
    ];

    /**
     * 请求方式post
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * @return mixed|void
     */
    function myConstruct()
    {
        $this->objPage = new Service_Page_UpdateOmsOrderInfo();
    }

    /**
     * @param array $data
     * @return array
     */
    public function format($data)
    {
        return $data;
    }
}