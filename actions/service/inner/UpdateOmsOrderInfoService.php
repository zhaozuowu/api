<?php
/**
 * @name Action_UpdateOmsOrderInfo
 * @desc 增量更新Oms子订单信息
 * @author hang.song02@ele.me
 */

class Action_Service_UpdateOmsOrderInfoService extends Orderui_Base_ServiceAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'order_info' => [
            'validate' => 'json|decode|required',
            'type' => 'array',
            'params' => [
                'parent_order_id'             => 'int|required',
                'order_id'             => 'int|required',
                'order_type'             => 'int|required',
                'parent_key'             => 'int|required',
                'order_exception'             => 'str',
                'skus' => [
                    'validate' => 'arr|required',
                    'type' => 'array',
                    'params' => [
                        'sku_id' => 'int|required',
                        'sku_amount' => 'int|required|min[1]',
                        'sku_exception' => 'str',
                        'sku_ext' => 'str',
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