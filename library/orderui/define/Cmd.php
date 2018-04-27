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
     * 创建门店退货订单
     * @var string
     */
    const CMD_CREATE_SHOP_RETURN_ORDER = 'create_shop_return_order';

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
