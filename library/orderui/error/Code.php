<?php

/**
 * @name Order_Error_Code
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
     * ral异常
     * @var integer
     */
    const RAL_ERROR = 200000;

    /**
     * 业态订单拆分失败
     * @var integer
     */
    const BUSINESS_ORDER_IS_SPLIT = 400001;

    /**
     * oms订单不存在
     * @var integer
     */
    const OMS_ORDER_IS_NOT_EXITED = 400002;

    /**
     * oms订单详情类型不合法
     * @var integer
     */
    const OMS_ORDER_DETAIL_TYPE_INVALID = 400003;

    /**
     * oms订单系统类型不合法
     * @var integer
     */
    const OMS_ORDER_SYS_TYPE_INVALID = 400004;
}
