<?php
/**
 * @name CancelLogisticsOrder.php
 * @desc CancelLogisticsOrder.php
 * @author yu.jin03@ele.me
 */
class Action_Service_CancelLogisticsOrder extends Orderui_Base_ServiceAction
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
        $this->objPage = new Service_Page_CancelLogisticsOrder();
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