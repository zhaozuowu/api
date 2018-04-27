<?php
/**
 * @name Warehouse.php
 * @desc Warehouse.php
 * @author yu.jin03@ele.me
 */
class Dao_Ral_Warehouse
{
    /**
     * @var Orderui_ApiRaler
     */
    protected $objApiRal;

    /**
     * @var string
     */
    const API_RALER_GET_WAREHOUSE_LIST = 'getwarehouselist';

    /**
     * Dao_Ral_Warehouse constructor.
     */
    public function __construct()
    {
        $this->objApiRal = new Orderui_ApiRaler();
    }

    /**
     * 根据region id 获取仓库信息
     * @param $intCityId
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function getWarehouseListByDistrictId($intDistrictId) {
        $ret = [];
        if (empty($intDistrictId)) {
            return $ret;
        }
        $req[self::API_RALER_GET_WAREHOUSE_LIST] = $intDistrictId;
        $ret = $this->objApiRal->getData($req);
        $ret = !empty($ret[self::API_RALER_GET_WAREHOUSE_LIST]['query_result']) ?
            $ret[self::API_RALER_GET_WAREHOUSE_LIST]['query_result'] : [];
        return $ret;
    }
}