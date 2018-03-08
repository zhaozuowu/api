<?php
/**
 * @name Service_Page_SplitBusinessOrder
 * @desc Service_Page_SplitBusinessOrder
 * @author hang.song02@ele.me
 */

class Service_Page_SplitBusinessOrder implements Orderui_Base_Page
{
    /**
     * @var Service_Data_SplitBusinessOrder
     */
    protected $objData;

    /**
     * Service_Page_SplitBusinessOrder constructor.
     */
    public function __construct()
    {
        $this->objData = new Service_Data_BusinessOrder();
    }

    /**
     * @param array $arrInput
     * @return array
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function execute($arrInput)
    {
        //拆分业态订单
        $arrOrderList = $this->objData->splitBusinessOrder($arrInput['business_order_id']);
        //转发拆分完毕业态订单

        return $arrOrderList;
    }
}