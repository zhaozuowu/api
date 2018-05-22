<?php
/**
 * @name Action_Service_CancelLogisticsReturnOrder
 * @desc 取消撤点单
 * @author hang.song02@ele.me
 */
class Action_Service_CancelLogisticsReturnOrder extends Orderui_Base_ServiceAction
{
    /**
     * arrInput
     * @var array
     */
    protected $arrInputParams = [
        'logistics_order_id' => 'int|required',
        'cancelRemark' => 'str|required|len[255]',
    ];

    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * init
     * @return mixed|void
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_CancelLogisticsReturnOrder();
    }

    /**
     * format
     * @param $arrRet
     * @return mixed
     */
    public function format($arrRet)
    {
        return $arrRet;
    }
}