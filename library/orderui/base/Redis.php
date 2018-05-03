<?php
/**
 * @name Orderui_Base_Redis
 * @desc redis base
 * @author wanggang(wanggang01@iwaimai.baidu.com)
 */
class Orderui_Base_Redis
{
    /**
     * nscm redis connection name
     * @var string
     */
    const REDIS_OMS = 'redis_nscm_purchse';

    /**
     * redis object
     * @var Redis
     */
    protected $objRedisConn;

    /**
     * init
     */
    public function __construct()
    {
        $this->objRedisConn = Wm_Service_RedisMgr::getInstanceByBns(self::REDIS_OMS);
    }
}
