<?php
/**
 * @name Orderui_Define_Cmd
 * @desc Orderui_Define_Cmd
 * @author jinyu02@iwaimai.baidu.com
 */
class Orderui_Define_Cmd
{
    /**
     * 默认topic名称
     * @var string
     */
    const CMD_TOPIC = 'order';

    /**
     * nwms topic
     * @var string
     */
    const OMS_ORDER_TOPIC = 'omsorderui';

    /**
     * nwms topic
     * @var string
     */
    const OMS_ENS_TOPIC = 'oms_ens';


   /**
     * cmd signup stockout order
     * @var string
     */
    const CMD_SIGNUP_STOCKOUT_ORDER = 'signup_stockout_order';


    /**
     * 创建销退入库单
     * @var string
     */
    const CMD_CREATE_RETURN_STOCKIN_ORDER = 'create_sales_return_stockin_order';

    /**
     * 创建逆向订单
     * @var string
     */
    const CMD_CREATE_REVERSE_OMS_ORDER = 'create_reverse_oms_order';

    /**
     * 创建撤掉单
     * @var string
     */
    const CMD_CREATE_REVERSE_SHELF_ORDER = 'create_reverse_shelf_order';

    /**
     * 创建门店退货订单
     * @var string
     */
    const CMD_CREATE_SHOP_RETURN_ORDER = 'create_shop_return_order';
    /**
     * oms订单创建
     * @var string
     */
    const CMD_CREATE_OMS_ORDER = 'create_oms_order';

    /**
     * 通知门店oms订单创建结果
     * @var string
     */
    const CMD_NOTIFY_ISS_OMS_ORDER_CREATE = 'notify_iss_oms_order_create';

    /**
     * 通知门店退货单创建结果
     * @var string
     */
    const CMD_NOTIFY_ISS_OMS_RETURN_ORDER_CREATE = 'notify_iss_oms_return_order_create';

    /**
     * 创建货架销退入库单
     * @var string
     */
    const CMD_CREATE_SHELF_RETURN_ORDER = 'create_shelf_return_order';
    /**
     * 通知TMS货架撤点盘点数据
     * @var string
     */
    const CMD_NOTIFY_TMS_SHELF_RETURN_ORDER = 'notify_tms_shelf_return_order';

    /**
     * cmd trigger event
     * @var string
     */
    const CMD_EVENT_SYSTEM = 'event_system';

    /**
     * wmq使用的默认配置
     * @var array
     */
    const DEFAULT_WMQ_CONFIG = [
        'Topic' => self::OMS_ORDER_TOPIC,
        'Key' => '',
        'serviceName' => 'wmqproxy',
    ];
}
