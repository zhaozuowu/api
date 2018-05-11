<?php
/**
 * Created by PhpStorm.
 * User: think
 * Date: 2017/11/17
 * Time: 18:01
 */
class  Orderui_Define_Const
{
    /**
     * 未删除
     */
    const NOT_DELETE = 1;

    /**
     * 已删除
     */
    const IS_DELETE = 2;

    /**
     * sku is active
     * @var integer
     */
    const IS_ACTIVE = 1;

    /**
     * sku is not active
     * @var integer
     */
    const NOT_ACTIVE = 2;

    /**
     * 取消物流单成功
     * @var integer
     */
    const CANCEL_SUCCESS = 1;

    /**
     * 取消物流单失败
     * @var integer
     */
    const CANCEL_FAILED = 2;

    /**
     * delete
     * @var array
     */
    const DELETE_MAP = [
        self::NOT_DELETE => '未删除',
        self::IS_DELETE  => '已删除',
    ];

    /**
     * get方法
     */
    const METHOD_GET = 1;

    /**
     * post方法
     */
    const METHOD_POST = 2;

    /**
     * 空数据默认显示为格式
     */
    const DEFAULT_EMPTY_RESULT_STR = '--';

    /**
     * oms订单下发系统类型nwms
     */
    const ORDER_SYS_NWMS = 1;

    /**
     * oms订单下发系统类型erp
     * @var integer
     */
    const ORDER_SYS_ERP = 2;

    /**
     * oms订单下发系统类型tms
     */
    const ORDER_SYS_TMS = 3;

    /**
     * NWMS ORDER 类型范围
     */
    const ORDER_SYS_TYPE = [
        self::ORDER_SYS_NWMS,
    ];

    /**
     * 运单号类型
     * @var integer
     */
    const NWMS_ORDER_TYPE_SHIPMENT_ORDER = 301;
    /**
     * NWMS ORDER 类型范围
     */
    const NWMS_ORDER_TYPE = [
        Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
        Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT,
        Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_RESERVE,
        Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_IN,
    ];

    /**
     * oms子单类型向上追溯关系
     */
    const ORDER_PARENT_ORDER_TYPE = [
        Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
    ];

    /**
     * @var integer
     * 事件回调无效父节点
     */
    const OMS_EVENT_INVALID_PARENT_KEY = -1;

    /**
     * @var integer
     * 事件回调无效父节点
     */
    const NWMS_ORDER_CREATE_STATUS_FAILED = 2;

    /**
     * @var integer
     * 事件回调无效父节点
     */
    const NWMS_ORDER_CREATE_STATUS_SUCCESS = 1;

    /**
     * @var integer
     * oms订单处理失败
     */
    const OMS_ORDER_DEAL_FAILED = 3;

    /**
     * @var integer
     * oms订单处理完成
     */
    const OMS_ORDER_DEAL_SUCCESS = 1;

    /**
     * @var integer
     * 业态订单SKU异常状态 正常
     */
    const BUSINESS_ORDER_SKU_NORMAL = 1;

    /**
     * @var integer
     * 业态订单SKU异常状态 异常
     */
    const BUSINESS_ORDER_SKU_EXCEPTION = 2;

    /**
     * 业态订单货位调整事件
     * @var integer
     */
    const BUSINESS_ORDER_EVENT_TYPE_ADJUST = 1;

    /**
     * 业态订单计划下架事件
     * @var integer
     */
    const BUSINESS_ORDER_EVENT_TYPE_PLAN_PUTOFF = 2;

    /**
     * 业态订单下架事件
     * @var integer
     */
    const BUSINESS_ORDER_EVENT_TYPE_PUTOFF = 3;

    /**
     * 业态订单签收事件
     * @var integer
     */
    const BUSINESS_ORDER_EVENT_TYPE_SIGNUP = 4;

    /**
     * 业态订单事件类型
     * @var integer
     */
    const BUSINESS_ORDER_EVENT_TYPE_MAP = [
        self::BUSINESS_ORDER_EVENT_TYPE_ADJUST => '货位调整',
        self::BUSINESS_ORDER_EVENT_TYPE_PLAN_PUTOFF => '计划下架',
        self::BUSINESS_ORDER_EVENT_TYPE_PUTOFF => '下架',
        self::BUSINESS_ORDER_EVENT_TYPE_SIGNUP => '签收',
    ];
     /**
     * @var integer
     * 取消物流单延迟读库微秒数
     */
    const CANCEL_DELAY_MICRO_SECONDS = 200000;
}
