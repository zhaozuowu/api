<?php
/**
 * @name Service_Data_NWmsOrder
 * @desc Service_Data_NWmsOrder
 * @author hang.song02@ele.me
 */

class Service_Data_NWmsOrder
{
    /**
     * @var Dao_Ral_NWmsOrder
     */
    protected $objDao;

    public function __construct()
    {
        $this->objDao = new Dao_Ral_NWmsOrder();
    }

    /**
     * 创建NWms订单
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function createNWmsOrder($arrBusinessOrderInfo)
    {
        return $this->objDao->createNWmsOrder($arrBusinessOrderInfo);
    }
}