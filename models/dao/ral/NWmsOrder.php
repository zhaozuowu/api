<?php
/**
 * @name Dao_Ral_NWmsOrder
 * @desc Dao_Ral_NWmsOrder
 * @author hang.song02@ele.me
 */

class Dao_Ral_NWmsOrder
{
    /**
     * api raler
     * @var Orderui_ApiRaler
     */
    protected $objApiRal;

    /**
     * create nwms order
     * @var string
     */
    const API_RALER_CREATE_NWMS_ORDER = 'createnwmsorder';

    /**
     * init
     */
    public function __construct()
    {
        $this->objApiRal = new Orderui_ApiRaler();
    }

    /**
     * 创建NWms订单
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function createNWmsOrder($arrBusinessOrderInfo)
    {
        $req[self::API_RALER_CREATE_NWMS_ORDER] = $arrBusinessOrderInfo;
        $ret = $this->objApiRal->getData($req);
        $ret = !empty($ret[self::API_RALER_CREATE_NWMS_ORDER]) ? $ret[self::API_RALER_CREATE_NWMS_ORDER] : [];
        return $ret;
    }
}
