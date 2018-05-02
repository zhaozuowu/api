<?php

/**
 * @name Action_Service_UpdateStockInOrderSkuPlanAmount
 * @desc 修正销退入库单计划入库数
 * @author wende.chen@ele.me
 */
class Action_Service_UpdateStockInOrderSkuPlanAmount extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'stockin_order_id' => 'str|required',
        'sku_info_list' => [
            'sku_id' => 'int|required|min[0]',
            'sku_amount' => 'int|required|min[0]',
            ],
    ];

    /**
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_UpdateStockInOrderSkuPlanAmount();
    }

    /**
     * @desc format result
     * @param array $data
     * @return array
     */
    public function format($data)
    {
        return $data;
    }
}