<?php

/**
 * @name Orderui_Error_Code
 * @desc Error Code
 * 占位说明：
 * Wm_Error_Code:100000~200000
 * APPXXX_Error_Code 系统异常:200000~300000
 * APPXXX_Error_Code 业务异常:300000~400000
 * @auth wanggang01@iwaimai.baidu.com
 */
class Orderui_Error_Code extends Wm_Error_Code
{
    /**
     * 正常返回
     * @var integer
     */
    const SUCCESS = 0;

    /**
     * 业态订单类型错误
     * @var integer
     */
    const NWMS_BUSINESS_FORM_ORDER_TYPE_ERROR = 340003;

    /**
     * 业态订单补货类型错误
     * @var integer
     */
    const NWMS_BUSINESS_FORM_ORDER_SUPPLY_TYPE_ERROR = 340004;

    /**
     * 预计送达时间不合法
     * @var integer
     */
    const NWMS_ORDER_STOCKOUT_EXPECT_ARRIVE_TIME_ERROR = 340019;/**

   /**
    * 纬度错误
    * @var integer
    */
    const NWMS_ORDER_STOCKOUT_LATITUDE_ERROR = 340021;

    /**
     * 经度错误
     * @var integer
     */
    const NWMS_ORDER_STOCKOUT_LONGITUDE_ERROR = 340022;

    /**
     * 坐标来源标识错误
     * @var integer
     */
    const NWMS_ORDER_CUSTOMER_LOCATION_SOURCE_ERROR = 340023;

    /**
     * 无人货架信息错误
     * @var integer
     */
    const NWMS_ORDER_STOCKOUT_SHELF_ERROR = 340024;

    /**
     * 创建nwms订单失败
     * @var integer
     */
    const NWMS_ORDER_CREATE_ERROR = 350025;

    /**
     * ral异常
     * @var integer
     */
    const RAL_ERROR = 200000;

    /**
     * 业态订单拆分失败
     * @var integer
     */
    const BUSINESS_ORDER_IS_SPLIT = 350030;

    /**
     * oms订单不存在
     * @var integer
     */
    const OMS_ORDER_IS_NOT_EXITED = 350031;

    /**
     * oms订单详情类型不合法
     * @var integer
     */
    const OMS_ORDER_DETAIL_TYPE_INVALID = 350032;

    /**
     * oms订单系统类型不合法
     * @var integer
     */
    const OMS_ORDER_SYS_TYPE_INVALID = 350033;

    /**
     * order system detail订单已经存在
     * @var integer
     */
    const ORDER_SYS_DETAIL_IS_EXITED = 350034;

    /**
     * oms权限校验失败
     * @var integer
     */
    const OMS_CHECK_AUTHORITY_ERROR = 350001;
}
