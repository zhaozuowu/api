<?php
/**
 * @name Action_Service_SyncDriverInfo
 * @desc 接收TMS司机信息，转发到货架(配车）
 * @author wende.chen@ele.me
 */
class Action_Service_SyncDriverInfo extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'shipment_order_id' => 'str|required',
        'driver_info' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sex' => 'int|required',
                'name' => 'str|required',
                'contact_phone' => 'str|required',
                'id' => 'str|required',
            ],
        ],
    ];

    /**
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_SyncDriverInfo();
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