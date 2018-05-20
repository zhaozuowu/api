<?php
/**
 * @name Notifyminimartreverseordercreate.php
 * @desc Notifyminimartreverseordercreate.php
 * @author yu.jin03@ele.me
 */

class Service_Page_Orderui_Commit_Notifyminimartreverseordercreate extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * Service_Page_Orderui_Commit_Notifyminimartreverseordercreate constructor.
     */
    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * 通知货架业态订单创建结果
     * @param $arrRequest
     * @throws Orderui_BusinessError
     */
    public function myExecute($arrRequest)
    {
        $intLogisticsOrderId = $arrRequest['logistics_order_id'];
        $intShipmentOrderId = $arrRequest['shipment_order_id'];
        $this->objDsBusinessFormOrder->notifyMiniMartReverseOrderCreate($intLogisticsOrderId, $intShipmentOrderId);
    }
}