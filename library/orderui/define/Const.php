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
        self::NWMS_ORDER_TYPE_ORDER,
        self::NWMS_ORDER_TYPE_STOCK_OUT,
        self::NWMS_ORDER_TYPE_RESERVE,
        self::NWMS_ORDER_TYPE_STOCK_IN,
    ];
    /**
     * NWMS订单类型 订单
     */
    const NWMS_ORDER_TYPE_ORDER = 1;
    /**
     * NWMS订单类型 出库单
     */
    const NWMS_ORDER_TYPE_STOCK_OUT = 2;
    /**
     * NWMS订单类型 预约入库单
     */
    const NWMS_ORDER_TYPE_RESERVE = 3;
    /**
     * NWMS订单类型 入库单
     */
    const NWMS_ORDER_TYPE_STOCK_IN = 4;

    const ORDER_PARENT_ORDER_TYPE = [
        self::ORDER_SYS_NWMS => [
            self::NWMS_ORDER_TYPE_STOCK_OUT => self::NWMS_ORDER_TYPE_ORDER,
        ],
    ];
}
