<?php
/**
 * @name Dao_Ral_Sku
 * @desc Dao_Ral_Sku
 * @author yu.jin03@ele.me
 */
class Dao_Ral_Sku
{
    /**
     * @var Orderui_ApiRailer
     */
    protected $objApiRal;

    /**
     * get sku infos
     * @var string
     */
    const API_RALER_GET_SKU_INFOS = 'getskuinfos';

    /**
     * init object
     */
    public function __construct()
    {
        $this->objApiRal = new Orderui_ApiRaler();
    }

    /**
     * get sku infos
     * @param int[] $arrSkuIds
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function getSkuInfos($arrSkuIds)
    {
        $intCountInput = count($arrSkuIds);
        $arrSkus = implode(',', $arrSkuIds);
        $req = [
            self::API_RALER_GET_SKU_INFOS => [
                'sku_ids' => $arrSkus,
            ],
        ];
        Bd_Log::debug('ral getSkuInfos input params: ' . json_encode($req));
        $ret = $this->objApiRal->getData($req);
        Bd_Log::debug('ral getSkuInfos out params: ' . json_encode($ret));
        $arrSkuInfos = [];
        if (empty($ret[self::API_RALER_GET_SKU_INFOS]['skus'])) {
            return $arrSkuInfos;
        }
        foreach ($ret[self::API_RALER_GET_SKU_INFOS]['skus'] as $row) {
            $arrSkuInfos[$row['sku_id']] = $row;
        }
        $intCountOutput = count($arrSkuInfos);
        if ($intCountInput != $intCountOutput) {
            Bd_Log::warning('io sku count not equal');
        }
        return $arrSkuInfos;
    }
}