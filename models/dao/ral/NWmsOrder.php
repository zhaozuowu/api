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
    /*
     * signup stockout order
     * @var string
     */
    const API_SIGNUP_STOCKOUT_ORDER = 'signupstockoutorder';
    /*
     * create sales return stockin order
     * @var string
     */
    const API_CREATE_SALES_RETURN_STOCKIN_ORDER = 'createsalereturnstockinorder';

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

    /**
     * 签收nwms出库单
     * @param  array $arrSignupInfo
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function signupStockoutOrder($arrSignupInfo)
    {
        $req[self::API_SIGNUP_STOCKOUT_ORDER] = $arrSignupInfo;
        Bd_Log::trace(sprintf("signup nwms order request params %s", json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("signup nwms order response %s", json_encode($ret)));
        $ret = !empty($ret[self::API_SIGNUP_STOCKOUT_ORDER]) ? $ret[self::API_SIGNUP_STOCKOUT_ORDER] : [];
        return $ret;
    }
    /**
     * 创建销退入库单
     * @param  array $arrData
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function CreateSalesReturnStockinOrder($arrData)
    {
        $req[self::API_CREATE_SALES_RETURN_STOCKIN_ORDER] = $arrData;
        Bd_Log::trace(sprintf("create sales return stockin order request params %s", json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("create sales return stockin order response %s", json_encode($ret)));
        $ret = !empty($ret[self::API_CREATE_SALES_RETURN_STOCKIN_ORDER]) ? $ret[self::API_CREATE_SALES_RETURN_STOCKIN_ORDER] : [];
        return $ret;
    }
}
