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
    const NWMS_ORDER_STOCKOUT_EXPECT_ARRIVE_TIME_ERROR = 340019;

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
     * order system detail订单不存在
     * @var integer
     */
    const ORDER_SYS_DETAIL_NOT_EXITED = 350035;

    /**
     * oms权限校验失败
     * @var integer
     */
    const OMS_CHECK_AUTHORITY_ERROR = 350001;

    /**
     * 事件回调顺序异常
     */
    const OMS_EVENT_CALLBACK_INVALID = 350035;
    /**
     * 事件回调指定父节点异常
     */
    const OMS_EVENT_CALLBACK_PARENT_KEY_INVALID = 350036;

    /**
     * 接入事件失败
     */
    const OMS_TRIGGER_EVENT_FAIL = 360005;
    /**
     * 接入系统不存在
     */
    const OMS_NOT_FOUND_CLIENT = 360006;
    /**
     * 接入事件不存在
     */
    const OMS_NOT_FOUND_EVENT = 360007;

    /**
     * OMS取消物流单失败
     * @var integer
     */
    const OMS_ORDER_CANCEL_FAILED = 350037;

    /**
     * 找不到关联的出库单
     * @var integer
     */
    const OMS_NWMS_ORDER_NOT_FOUND = 350038;

    /**
     * 找不到关联的tms运单
     * @var integer
     */
    const OMS_TMS_ORDER_NOT_FOUND = 350039;
    /**
     * 找不到运单
     */
    const OMS_NOT_FOUND_SHIPMENT_ORDER = 360001;
    /**
     * 找不到出库单
     */
    const OMS_NOT_FOUND_STOCKOUT_ORDER = 360002;
    /**
     * 签收运单失败
     */
    const OMS_SIGNUP_SHIPMENT_ORDER_FAIL = 360003;
    /**
     * 签收出库单失败
     */
    const OMS_SIGNUP_STOCKOUT_ORDER_FAIL = 360004;
    /**
     * 运单签收失败
     */
    const OMS_TMS_SIGNUP_SHIPMENT_ORDER_FAILED = 360005;
    /**
     * 创建销退入库单失败
     */
    const OMS_CREATE_SALE_RETURN_STOCKIN_ORDER_FAIL = 360006;
    /**
     * OMS调用NWMS更新销退入库单计划入库商品数失败
     */
    const OMS_UPDATE_NWMS_STOCKIN_ORDER_SKU_PLAN_AMOUNT_FAIL = 360007;
    /**
     * OMS调用SHOP更新出库单拣货商品数失败
     */
    const OMS_UPDATE_SHOP_STOCKOUT_SKU_PICKUP_AMOUNT_FAIL = 360008;
    /**
     * 批量创建销退入库单失败
     */
    const OMS_BATCH_CREATE_SALE_RETURN_STOCKIN_ORDER_FAIL = 360009;
    /**
     * 通知门店退货单创建结果失败
     */
    const OMS_NOTIFY_CREATE_SHOP_RETURN_ORDER_FAIL = 360010;
    /**
     * 找不到关联订单
     */
    const OMS_MAP_ORDER_NOT_FOUND = 370001;

    /**
     * sku参数错误
     * @var integer
     */
    const OMS_SKU_INFO_PARAMS_ERROR = 370002;

    /**
     * 无效的sku信息
     * @var integer
     */
    const OMS_SKU_INFO_INVALID = 370003;

    /**
     * 通知门店创建信息失败
     * @var integer
     */
    const OMS_NOTIFY_ISS_CREATE_RESULT_FAILED = 370004;

    /**
     * 通知门店失败
     * @var integer
     */
    const OMS_RECALL_SHELF_CREATE_SHIPMENT_ORDER_FAILED = 370005;

    /**
     * 获取仓库信息失败
     * @var integer
     */
    const OMS_GET_WAREHOUSE_INFO_FAILED = 370006;
}
