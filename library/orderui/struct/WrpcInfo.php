<?php
/**
 * @name Orderui_Struct_WrpcInfo
 * @desc Orderui_Struct_WrpcInfo
 * @author bochao.lv@ele.me
 */

/**
 * Class Orderui_Struct_WrpcInfo
 * @property string meta
 * @property array data
 * @method static Orderui_Struct_WrpcInfo build(array $meta, array $data)
 */
class Orderui_Struct_WrpcInfo extends Orderui_Base_Struct
{
    protected $arrProperty = [
        'meta',
        'data',
    ];
}