<?php
/**
 * @name Service_Page_UpdateStockInOrderSkuPlanAmount
 * @desc 更新销退入库单计划入库数
 * @author wende.chen@ele.me
 */
class Service_Page_UpdateStockInOrderSkuPlanAmount
{
    /**
     * @var objData
     */
    protected $objData;

    /**
     * init object
     */
    public function __construct()
    {
        $this->objData = new Service_Data_NWmsOrder();
    }

    /**
     * @desc 更新销退入库单计划入库数
     * @param array $arrInput
     * @return array
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        //orderSystemDetailId
        $strOrderSystemDetailId = strval($arrInput['stockin_order_id']);
        $arrSkuInfoList = $arrInput['sku_info_list'];
        return $this->objData->updateStockInOrderSkuPlanAmount($strOrderSystemDetailId, $arrSkuInfoList);
    }
}