<?php
/**
 * @name Action_Service_SyncRejectAllInfo
 * @desc 接收TMS整单拒收信息，转发货架
 * @author wende.chen@ele.me
 */
class Action_Service_SyncRejectAllInfo extends Orderui_Base_ServiceAction
{
    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    protected $arrInputParams = [
        'shipment_order_id' => 'str|required',
        'reject_remark' => 'str',
        'reject_info' => 'str|required',
    ];

    /**
     * init Object
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_SyncRejectAllInfo();
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