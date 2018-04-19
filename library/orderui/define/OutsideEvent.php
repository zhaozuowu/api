<?php
/**
 * @name Orderui_Define_OutsideEvent
 * @desc Orderui_Define_OutsideEvent
 * @author bochao.lv@ele.me
 */

class Orderui_Define_OutsideEvent
{
    /**
     * event name
     */
    const EVENT_NAME_CONFIRM_STOCKIN_ORDER = 'confirmStockinOrder';

    /**
     * out side event validate
     */
    const OUTSIDE_EVENT_VALIDATE = [
        self::EVENT_NAME_CONFIRM_STOCKIN_ORDER => [
            'stockin_order_id' => 'regex|patern[/^((SIO)\d{13})?$/]',
            'stockin_order_remark' => 'strutf8',
            'sku_info_list' => [
                'validate' => 'json|required|decode',
                'type' => 'array',
                'params' => [
                    'sku_id' => 'int|required|min[1000000]|max[9999999]',
                    'real_stockin_info' => [
                        'validate' => 'arr|required|decode',
                        'type' => 'array',
                        'params' => [
                            'amount' => 'int|required',
                            'sku_good_amount' => 'int|required',
                            'sku_defective_amount' => 'int|required',
                            'expire_date' => 'int|required',
                        ]
                    ],
                ],
            ],
        ],
    ];

}