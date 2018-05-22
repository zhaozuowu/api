<?php
/**
 * @name Service_Page_CancelLogisticsReturnOrder
 * @desc 取消撤点单
 * @author hang.song02@ele.me
 */
class Service_Page_CancelLogisticsReturnOrder implements Orderui_Base_Page
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * Service_Page_CancelLogisticsOrder constructor.
     */
    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * @param array $arrInput
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function execute($arrInput)
    {
        return $this->objDsBusinessFormOrder->cancelLogisticsReturnOrder($arrInput['logistics_order_id'],
                                                            $arrInput['cancelRemark']);
    }
}