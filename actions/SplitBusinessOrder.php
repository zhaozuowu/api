<?php
/**
 * @name Action_SplitBusinessOrder
 * @desc 拆分业态订单
 * @author hang.song02@ele.me
 */

class Action_SplitBusinessOrder extends Orderui_Base_Action
{
    protected $arrInputParams = [
        'business_order_id' => 'int|required',
    ];

    /**
     * @var int request method
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * construct
     */
    function myConstruct()
    {
       $this->objPage = new Service_Page_SplitBusinessOrder();
    }

    /**
     * @param  array $data
     * @return array $data
     */
    public function format($data)
    {
        return $data;
    }

}