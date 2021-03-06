<?php

/**
 * @name Orderui_Error_Message
 * @desc Error Code
 * @auth yu.jin03@ele.me
 */
class Orderui_Error_Message extends Wm_Error_Message
{
    protected $_disp_app_err_msg = [
        Orderui_Error_Code::SUCCESS => '',
        Orderui_Error_Code::RAL_ERROR => 'X',
        Orderui_Error_Code::SUCCESS => '',
        Orderui_Error_Code::RAL_ERROR => 'X',
        Orderui_Error_Code::NWMS_BUSINESS_FORM_ORDER_SUPPLY_TYPE_ERROR => '业态订单补货类型错误',
        Orderui_Error_Code::NWMS_BUSINESS_FORM_ORDER_TYPE_ERROR => '业态订单类型错误',
        Orderui_Error_Code::NWMS_ORDER_CUSTOMER_LOCATION_SOURCE_ERROR => '经纬度坐标来源错误',
        Orderui_Error_Code::NWMS_ORDER_STOCKOUT_EXPECT_ARRIVE_TIME_ERROR => '预计送达时间不能为过去时间',
        Orderui_Error_Code::NWMS_ORDER_STOCKOUT_LATITUDE_ERROR => '纬度超出范围',
        Orderui_Error_Code::NWMS_ORDER_STOCKOUT_LONGITUDE_ERROR => '经度超出范围',
        Orderui_Error_Code::NWMS_ORDER_STOCKOUT_SHELF_ERROR => '无人货架信息错误',
        Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT => '业态订单拆分失败',
        Orderui_Error_Code::OMS_ORDER_IS_NOT_EXITED => 'oms订单不存在',
        Orderui_Error_Code::OMS_ORDER_DETAIL_TYPE_INVALID => 'oms订单详情类型不合法',
        Orderui_Error_Code::OMS_ORDER_SYS_TYPE_INVALID => 'oms订单系统类型不合法',
        Orderui_Error_Code::ORDER_SYS_DETAIL_IS_EXITED => '订单已经存在',
        Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR => '创建nwms订单失败',
        Orderui_Error_Code::OMS_CHECK_AUTHORITY_ERROR => 'oms权限校验失败',
        Orderui_Error_Code::OMS_EVENT_CALLBACK_INVALID => '事件回调顺序异常',
        Orderui_Error_Code::OMS_EVENT_CALLBACK_PARENT_KEY_INVALID => '事件回调指定父节点异常',
        Orderui_Error_Code::OMS_TRIGGER_EVENT_FAIL => '接入事件失败',
        Orderui_Error_Code::OMS_NOT_FOUND_CLIENT   => '接入系统不存在',
        Orderui_Error_Code::OMS_NOT_FOUND_EVENT   => '接入事件不存在',
        Orderui_Error_Code::OMS_NWMS_ORDER_NOT_FOUND => '没有关联的出库单',
        Orderui_Error_Code::OMS_TMS_ORDER_NOT_FOUND => '没有关联的运单',
        Orderui_Error_Code::OMS_NOT_FOUND_SHIPMENT_ORDER => '找不到运单',
        Orderui_Error_Code::OMS_NOT_FOUND_STOCKOUT_ORDER => '找不到出库单',
        Orderui_Error_Code::OMS_TMS_SIGNUP_SHIPMENT_ORDER_FAILED => '轻舟系统运单签收失败',
        Orderui_Error_Code::OMS_MAP_ORDER_NOT_FOUND => '找不到关联订单',
        Orderui_Error_Code::OMS_NOTIFY_SHELF_DRIVER_INFO_FAIL => 'OMS通知货架更新司机信息失败',
        Orderui_Error_Code::OMS_NOTIFY_SHELF_SHIPMENT_STATUS_FAIL => 'OMS通知货架更新运单状态失败',
        Orderui_Error_Code::OMS_NOTIFY_SHELF_NWMS_ACCEPT_ORDER_SKUS_FAIL => 'OMS通知货架沧海揽收信息失败',
        Orderui_Error_Code::OMS_NOTIFY_TMS_CANCEL_SHIPMENT_ORDER_FAIL => 'OMS通知TMS取消运单失败',
        Orderui_Error_Code::OMS_NOTIFY_SHELF_SHIPMENT_REJECT_ALL_FAIL => 'OMS通知货架整单拒收消息失败',
        Orderui_Error_Code::OMS_SIGNUP_STOCKOUT_ORDER_FAIL => '签收出库单失败',
        Orderui_Error_Code::OMS_CREATE_SALE_RETURN_STOCKIN_ORDER_FAIL => '创建销退入库单失败',
        Orderui_Error_Code::OMS_UPDATE_NWMS_STOCKIN_ORDER_SKU_PLAN_AMOUNT_FAIL => 'OMS调用NWMS更新销退入库单计划入库商品数失败',
        Orderui_Error_Code::OMS_UPDATE_SHOP_STOCKOUT_SKU_PICKUP_AMOUNT_FAIL => 'OMS调用SHOP更新出库单拣货商品数失败',
        Orderui_Error_Code::OMS_SKU_INFO_PARAMS_ERROR => 'sku参数错误',
        Orderui_Error_Code::OMS_SKU_INFO_INVALID => '无效的sku',
        Orderui_Error_Code::OMS_BATCH_CREATE_SALE_RETURN_STOCKIN_ORDER_FAIL => '批量创建销退入库单失败',
        Orderui_Error_Code::OMS_NOTIFY_ISS_CREATE_RESULT_FAILED => '通知门店正向单创建结果失败',
        Orderui_Error_Code::OMS_NOTIFY_CREATE_SHOP_RETURN_ORDER_FAIL => '通知门店退货单创建结果失败',
        Orderui_Error_Code::ORDER_SYS_DETAIL_NOT_EXITED => 'oms订单不存在',
        Orderui_Error_Code::OMS_RECALL_SHELF_CREATE_SHIPMENT_ORDER_FAILED => '轻舟系统创建撤点运单失败',
        Orderui_Error_Code::OMS_GET_WAREHOUSE_INFO_FAILED => 'oms获取仓库信息失败',
        Orderui_Error_Code::OMS_SHELF_INFO_ERROR => '无人货架信息错误',
        Orderui_Error_Code::OMS_SOURCE_ORDER_NOT_FOUNT => '未找到关联的soruce_order单号',
        Orderui_Error_Code::OMS_BUSINESS_FORM_ORDER_NOT_FOUNT => '未找到关联的业态单',
        Orderui_Error_Code::STOCK_IN_ORDER_CANCEL_FAILED => '取消销退入库失败',
        Orderui_Error_Code::BACK_ORDER_NOTIFY_TMS_FAIL => '盘点通知TMS失败',
    ];

}
