<?php

/**
 * @name Order_Error_Message
 * @desc Error Code
 * @auth yu.jin03@ele.me
 */
class Order_Error_Message extends Wm_Error_Message
{
    protected $_disp_app_err_msg = [
        Order_Error_Code::SUCCESS => '',
        Order_Error_Code::RAL_ERROR => 'X',
    ];

}
