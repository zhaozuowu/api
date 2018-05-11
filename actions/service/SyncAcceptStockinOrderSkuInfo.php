<?php
/**
 * @name Action_Service_SyncRejectAllInfo
 * @desc 接收TMS整单拒收信息，转发货架
 * @author wende.chen@ele.me
 */
class Action_Service_SyncAcceptStockinOrderSkuInfo extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'logistic_order_id' => 'str|required',
        'shipment_order_id' => 'str|required',
        'sku_info' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required|min[0]',
                'distribute_info' => [
                    'validate' => 'arr|required',
                    'type' => 'array',
                    'params' => [
                        'expire_date' => 'int|required|min[0]',
                        'amount' => 'int|required|min[0]',
                    ],
                ],
            ],
        ],
    ];

    /**
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_SyncAcceptStockinOrderSkuInfo();
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