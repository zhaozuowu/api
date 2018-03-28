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
        Orderui_Error_Code::OMS_NOT_FOUND_SHIPMENT_ORDER => '找不到运单',
        Orderui_Error_Code::OMS_NOT_FOUND_STOCKOUT_ORDER => '找不到出库单',
    ];

}
