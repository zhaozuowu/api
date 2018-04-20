<?php
/**
 * @name Orderui_Struct_WrpcInfo
 * @desc Orderui_Struct_WrpcInfo
 * @author bochao.lv@ele.me
 */

/**
 * Class Orderui_Struct_WrpcInfo
 * @property string routing_key
 * @property array data
 * @method __construct(string $routing_key, array $data)
 */
class Orderui_Struct_WrpcInfo extends Orderui_Base_Struct
{
    protected $arrProperty = [
        'routing_key',
        'data',
    ];
}