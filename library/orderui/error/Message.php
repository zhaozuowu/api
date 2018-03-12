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
        Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT => '业态订单拆分失败',
        Orderui_Error_Code::OMS_ORDER_IS_NOT_EXITED => 'oms订单不存在',
        Orderui_Error_Code::OMS_ORDER_DETAIL_TYPE_INVALID => 'oms订单详情类型不合法',
        Orderui_Error_Code::OMS_ORDER_SYS_TYPE_INVALID => 'oms订单系统类型不合法',
        Orderui_Error_Code::ORDER_SYS_DETAIL_IS_EXITED => '订单已经存在',
    ];

}
