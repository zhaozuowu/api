<?php

/**
 * @name Action_Service_UpdateStockoutOrderSkuPickupInfo
 * @desc OMS接收NWMS出库单拣货信息
 * @author wende.chen@ele.me
 */
class Action_Service_UpdateStockoutOrderSkuPickupInfo extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'stockout_order_id' => 'str|required',
        'pickup_sku_info_list' => [
            'sku_id' => 'int|required|min[0]',
            'sku_amount' => 'int|required|min[0]',
            ],
    ];

    /**
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_UpdateStockoutOrderSkuPickupInfo();
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