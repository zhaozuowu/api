<?php
/**
 * @name Action_UpdateOmsOrderInfo
 * @desc 增量更新Oms子订单信息
 * @author hang.song02@ele.me
 */

class Action_UpdateOmsOrderInfo extends Orderui_Base_ApiAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'parent_order_id'             => 'int|required',
        'order_id'             => 'int|required',
        'children_order_id'             => 'int',
        'order_type'             => 'int|required',
        'order_sys_id'             => 'int|required',
        'order_exception'             => 'str',
        'skus' => [
            'validate' => 'json|decode|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'sku_amount' => 'int|required|min[1]',
                'sku_exception' => 'str',
                'sku_ext' => 'str',
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