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
     * @var Orderui_Ral_Api_Ral
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
        $this->objApiRal = new Orderui_Ral_Api_Ral();
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
        Bd_Log::trace(sprintf("create nwms order request params %s", json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("create nwms order response %s", json_encode($ret)));
        $ret = !empty($ret[self::API_RALER_CREATE_NWMS_ORDER]) ? $ret[self::API_RALER_CREATE_NWMS_ORDER] : [];
        return $ret;
    }
}
