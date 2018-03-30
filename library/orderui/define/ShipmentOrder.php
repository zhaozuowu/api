<?php
/**
 * Created by PhpStorm.
 * User: xhb
 * Date: 2018/3/26
 * Time: 下午4:47
 */
class Orderui_Define_ShipmentOrder
{
    /**
     * 签收状态
     * @var array
     */
    const  SHIPMENT_SIGINUP_ACCEPT_ALL= 1;
    const  SHIPMENT_SIGINUP_REJECT_ALL= 2;
    const  SHIPMENT_SIGINUP_ACCEPT_PART= 3;

    /**
     * 签收状态
     */
    const  SIGNUP_STATUS_LIST = [
        self::SHIPMENT_SIGINUP_ACCEPT_ALL  => '签收',
        self::SHIPMENT_SIGINUP_REJECT_ALL  => '拒收',
        self::SHIPMENT_SIGINUP_ACCEPT_PART => '部分签收',
    ];
}