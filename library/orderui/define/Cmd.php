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
    const NWMS_ORDER_TOPIC = 'nwmsorder';

    /**
     * nscm sync inbound
     * @var string
     */
    const NSCM_SYNC_INBOUND = 'nscmsyncinbound';

    /**
     * 创建出库单命令
     * @var string
     */
    const CMD_CREATE_STOCKOUT_ORDER = 'stockout_order_create';

    /**
     * 完成拣货命令
     * @var string
     */
    const CMD_FINISH_PRICKUP_ORDER  = 'stockout_order_finish_pickup';

    /**
     * 作废出库单命令
     * @var string
     */
    const CMD_DELETE_STOCKOUT_ORDER  = 'stockout_order_delete';


    /**
     * order statistics operate
     * @var string
     */
    const CMD_SYNC_FORM_STATISTICS = 'order_statistics_operate';

    /**
     * reserve order create
     * @var string
     */
    const CMD_CREATE_RESERVE_ORDER = 'reserve_order_create';

    /**
     * cmd sync inbound
     * @var string
     * @deprecated
     */
    const CMD_SYNC_INBOUND = 'sync_inbound';

    /**
     * cmd sync inbound nwms
     * @var string
     */
    const CMD_SYNC_INBOUND_NWMS = 'nscm_purchase_order_sync';

    /**
     * cmd signup stockout order
     * @var string
     */
    const CMD_SIGNUP_STOCKOUT_ORDER = 'signup_stockout_order';

    /**
     * cmd transmit signup data to tms
     * @var string
     */
    const CMD_TRANSMIT_SIGNUP_DATA = 'transmit_signup_data_to_tms';

    /**
     * 创建销退入库单
     * @var string
     */
    const CMD_CREATE_RETURN_STOCKIN_ORDER = 'create_sales_return_stockin_order';

    /**
     * wmq使用的默认配置
     * @var array
     */
    const DEFAULT_WMQ_CONFIG = [
        'Topic' => self::NWMS_ORDER_TOPIC,
        'Key' => '',
        'serviceName' => 'wmqproxy',
    ];
}
