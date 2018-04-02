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
     * pre cancel stockout order
     * @var string
     */
    const API_RALER_PRE_CANCEL_STOCKOUT_ORDER = 'precancelstockoutorder';

    /**
     * confirm cancel stockout order
     * @var string
     */
    const API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER = 'confirmcancelstockoutorder';

    /**
     * rollback cancel stockout order
     * @var string
     */
    const API_RALER_ROLLBACK_CANCEL_STOCKOUT_ORDER = 'rollbackcancelstockoutorder';

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
     * 预取消出库单
     * @param $intStockoutOrderId
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function preCancelStockoutOrder($intStockoutOrderId)
    {
        $req[self::API_RALER_PRE_CANCEL_STOCKOUT_ORDER]['stock_out_order_id'] = $intStockoutOrderId;
        Bd_Log::trace(sprintf("method[%s] req[%s]", __METHOD__, json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("method[%s] ret[%s]", __METHOD__, json_encode($ret)));
        $ret = !empty($ret[self::API_RALER_PRE_CANCEL_STOCKOUT_ORDER]) ? $ret[self::API_RALER_PRE_CANCEL_STOCKOUT_ORDER] : [];
        return $ret;
    }

    /**
     * 确认取消出库单
     * @param $intStockoutOrderId
     * @param $strRemark
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function confirmCancelStockoutOrder($intStockoutOrderId, $strRemark)
    {
        $req[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER]['stockout_order_id'] = $intStockoutOrderId;
        $req[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER]['remark'] = strval($strRemark);
        Bd_Log::trace(sprintf("method[%s] req[%s]", __METHOD__, json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("method[%s] ret[%s]", __METHOD__, json_encode($ret)));
        $ret = !empty($ret[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER]) ? $ret[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER] : [];
        return $ret;
    }

    /**
     * 回滚取消出库单
     * @param $intStockoutOrderId
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function rollbackCancelStockoutOrder($intStockoutOrderId)
    {
        $req[self::API_RALER_ROLLBACK_CANCEL_STOCKOUT_ORDER]['stock_out_order_id'] = $intStockoutOrderId;
        Bd_Log::trace(sprintf("method[%s] req[%s]", __METHOD__, json_encode($req)));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::trace(sprintf("method[%s] ret[%s]", __METHOD__, json_encode($ret)));
        $ret = !empty($ret[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER]) ? $ret[self::API_RALER_CONFIRM_CANCEL_STOCKOUT_ORDER] : [];
        return $ret;
    }

}
