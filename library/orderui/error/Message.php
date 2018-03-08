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
    ];

}
