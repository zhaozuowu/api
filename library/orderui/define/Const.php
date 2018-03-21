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
     * NWMS ORDER 类型范围
     */
    const ORDER_SYS_TYPE = [
        self::ORDER_SYS_NWMS,
    ];

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
}
