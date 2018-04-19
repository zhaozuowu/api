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
    const EVENT_NAME_EXAMPLE1 = 'eventExample1';

    const OUTSIDE_EVENT_VALIDATE = [
        self::EVENT_NAME_EXAMPLE1 => [
            'param1' => 'int|required',
            'param2' => 'string|required',
        ],
    ];

}