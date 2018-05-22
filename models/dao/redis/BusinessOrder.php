<?php
/**
 * @name Dao_Redis_ReserveOrder
 * @desc Dao_Redis_ReserveOrder
 * @author lvbochao@iwaimai.baidu.com
 */
class Dao_Redis_BusinessOrder extends Orderui_Base_Redis
{
    /**
     * business order key prefix
     * @var string
     */
    const KEY_PREFIX = 'oms:order:businessinfo:';

    /**
     * reverse businessformorder
     * @var string
     */
    const REVERSE_SOURCE_ORDER_KEY_PREFIX = 'oms:order:reverse:sourceorderid:';

    /**
     * shop return order
     * @var string
     */
    const SHOP_RETURN_ORDER_KEY_PREFIX = 'oms:order:shop:return:sourceorderid:';

    /**
     * batch create order params key
     * @var string
     */
    const BATCH_CREATE_ORDER_PARAMS_KEY_PREFIX = 'oms:order:batchcreate:nwmsorder:';

    /**
     * reverse source order过期时间
     * @var integer
     */
    const REVERSE_SOURCE_ORDER_KEY_EXPIRE_TIME = 3600;

    /**
     * 批量nwmsorder参数过期时间
     * @var integer
     */
    const BATCH_CREATE_ORDER_PARAMS_KEY_EXPIRE_TIME = 3600;
    /**
     * set business order info
     * @param  array $arrBusinessOrderInfo
     * @return string
     */
    public function setOrderInfo($arrBusinessOrderInfo)
    {
        $strBusinessOrderInfo = json_encode($arrBusinessOrderInfo);
        $strKey = $arrBusinessOrderInfo['business_form_order_id'];
        $strRedisKey = self::KEY_PREFIX . $strKey;
        Bd_Log::debug(sprintf('set redis, key[%s], data:%s', $strRedisKey, $strBusinessOrderInfo));
        $boolRes = $this->objRedisConn->set($strRedisKey, $strBusinessOrderInfo);
        Bd_Log::debug('set redis result: ' . json_encode($boolRes));
        return $strKey;
    }

    /**
     * get order by key
     * @param $strKey
     * @return array
     */
    public function getOrderInfo($strKey)
    {
        $strRedisKey = self::KEY_PREFIX . $strKey;
        Bd_Log::debug(sprintf('get from redis, key[%s]', $strRedisKey));
        $strInfo = $this->objRedisConn->get($strRedisKey);
        Bd_Log::debug(sprintf('get from redis, result: `%s`', $strInfo));
        $arrRet = json_decode($strInfo, true);
        return $arrRet;
    }

    /**
     * drop order info
     * @param $strKey
     * @return int
     */
    public function dropOrderInfo($strKey)
    {
        $strRedisKey = self::KEY_PREFIX . $strKey;
        Bd_Log::debug(sprintf('drop from redis, key[%s]', $strRedisKey));
        $intRet = $this->objRedisConn->del($strRedisKey);
        Bd_Log::debug(sprintf('drop from redis, result: `%s`', $intRet));
        return $intRet;
    }

    /**
     * set reverse source order
     * @param integer $intSourceOrderId
     * @return void
     */
    public function setReverseSourceOrderKey($intSourceOrderId) {
        $strKey = self::REVERSE_SOURCE_ORDER_KEY_PREFIX . $intSourceOrderId;
        $this->objRedisConn->incr($strKey);
        $this->objRedisConn->expire($strKey, self::REVERSE_SOURCE_ORDER_KEY_EXPIRE_TIME);
    }

    /**
     * get reverse source order
     * @param integer $intSourceOrderId
     * @return mixed
     */
    public function getReverseSourceOrder($intSourceOrderId) {
        $strKey = self::REVERSE_SOURCE_ORDER_KEY_PREFIX . $intSourceOrderId;
        return $this->objRedisConn->get($strKey);
    }

    /**
     * set reverse source order
     * @param integer $intSourceOrderId
     * @return void
     */
    public function setShopReturnOrderKey($intSourceOrderId) {
        $strKey = self::SHOP_RETURN_ORDER_KEY_PREFIX . $intSourceOrderId;
        $this->objRedisConn->incr($strKey);
        $this->objRedisConn->expire($strKey, self::REVERSE_SOURCE_ORDER_KEY_EXPIRE_TIME);
    }

    /**
     * get reverse source order
     * @param integer $intSourceOrderId
     * @return mixed
     */
    public function getShopReturnOrderKey($intSourceOrderId) {
        $strKey = self::SHOP_RETURN_ORDER_KEY_PREFIX . $intSourceOrderId;
        return $this->objRedisConn->get($strKey);
    }

    /**
     * 缓存每次批量创建nwmsorder的参数
     * @param $intSourceOrderId
     * @param $arrOrderList
     */
    public function setBatchCreateOrderParams($intSourceOrderId, $arrOrderList)
    {
        $strKey = self::BATCH_CREATE_ORDER_PARAMS_KEY_PREFIX . $intSourceOrderId;
        $strOrderList = json_encode($arrOrderList);
        $this->objRedisConn->set($strKey, $strOrderList);
    }

    /**
     * 获取每次批量创建nwmsorder的参数缓存
     * @param $intSourceOrderId
     * @return mixed
     */
    public function getBatchCreateOrderParams($intSourceOrderId)
    {
        $strKey = self::BATCH_CREATE_ORDER_PARAMS_KEY_PREFIX . $intSourceOrderId;
        $strOrderList = $this->objRedisConn->get($strKey);
        return json_decode($strOrderList, true);
    }
}