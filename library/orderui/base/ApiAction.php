<?php
/**
 * @name Orderui_Base_ApiAction
 * @desc api action基类
 * @author lvbochao@iwaimai.baidu.com
 */
abstract class Orderui_Base_ApiAction extends Orderui_Base_Action
{
    /**
     * 是否验证登陆
     *
     * @var boolean
     */
    protected $boolCheckLogin = false;

    /**
     * 判断是否有权限
     *
     * @var boolean
     */
    protected $boolCheckAuth = false;

    /**
     * 是否校内网IP
     *
     * @var boolean
     */
    protected $boolCheckIp = true;

    /**
     * show price switch
     * @var bool $boolHidePrice
     */
    protected $boolHidePrice = false;
}
