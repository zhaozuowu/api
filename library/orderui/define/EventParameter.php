<?php
/**
 * @name Orderui_Define_EventParameter
 * @desc 事件与参数的对应关系
 * @author huabang.xue@ele.me
 */
class Orderui_Define_EventParameter
{
    /*
     * 事件参数列表
     */
    const EVENT_PARAMETER_LIST = [
        //签收运单事件参数
        Orderui_Define_Event::EVENT_SIGNUP_SHIPMENT_ORDER => [
            'shipment_order_id' => 'str|required',
            'signup_status' => 'int|required',
            'signup_skus' => 'arr|required',
            'offshelf_skus' => 'arr',
            'adjust_skus' => 'arr',
        ],
        //增量更新Oms子订单信息
        Orderui_Define_Event::EVENT_UPDATE_OMS_ORDER_INFO => [
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
        ],

    ];
}