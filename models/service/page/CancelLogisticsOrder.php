<?php
/**
 * @name CancelLogisticsOrder.php
 * @desc CancelLogisticsOrder.php
 * @author yu.jin03@ele.me
 */
class Service_Page_CancelLogisticsOrder implements Orderui_Base_Page
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
     * @throws Nscm_Exception_Error
     */
    public function execute($arrInput)
    {
        return $this->objDsBusinessFormOrder->cancelLogisticsOrder($arrInput['logistics_order_id'],
                                                            $arrInput['cancelRemark']);
    }
}