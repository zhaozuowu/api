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
    const EVENT_NAME_CONFIRM_STOCKIN_ORDER = 'confirmStockinOrder';  // confirm stockin
    const EVENT_NAME_DELIVERY_ORDER_TMS = 'deliveryOrder';  // finish collect
    const EVENT_NAME_BATCH_PICKING_AMOUNT = 'batchPickingAmount'; //batch picking amount

    /**
     * out side event validate
     */
    const OUTSIDE_EVENT_VALIDATE = [
        self::EVENT_NAME_CONFIRM_STOCKIN_ORDER => [
            'stockin_order_id' => 'int|required',
            'shipment_order_id' => 'int|required',
            'biz_type'  => 'int|required',
            'sku_info_list' => [
                'validate' => 'json|required|decode',
                'type' => 'array',
                'params' => [
                    'sku_id' => 'int|required|min[1000000]|max[9999999]',
                    'sku_amount' => 'int|required',
                ],
            ],
        ],
        self::EVENT_NAME_DELIVERY_ORDER_TMS => [
            'stockout_order_id' => 'int|required',
        ],
        self::EVENT_NAME_BATCH_PICKING_AMOUNT => [
            'batch_pickup_info' => [
                'validate' => 'arr|required|decode',
                'type' => 'array',
                'params' => [
                    'stockout_order_id' => 'int|required',
                    'shipment_order_id' => 'int|required',
                    'pickup_skus' => [
                        'validate' => 'arr|required|decode',
                        'type' => 'array',
                        'params' => [
                            'sku_id' => 'int|required',
                            'pickup_amount' => 'int|required',
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * outside event transform, for base type
     */
    const OUTSIDE_EVENT_TRANSFORM = [
        self::EVENT_NAME_DELIVERY_ORDER_TMS => [
            'stockout_order_id',
        ],
    ];

}