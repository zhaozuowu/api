<?php
/**
 * @name OrderuiService.php
 * @desc
 * @author: bochao.lv@ele.me
 * @createtime: 2018/6/4 11:23
 */

class Controller_OrderuiService extends Nscm_Base_ControllerService
{
    public $arrMap = [
        'Action_Service_DeliveryOrderService' => 'actions/service/inner/DeliveryOrderService.php',
        'Action_Service_UpdateOmsOrderInfoService' => 'actions/service/inner/UpdateOmsOrderInfoService.php',
    ];

    public function DeliveryOrderService($arrRequest)
    {
        $objAction = new Action_Service_DeliveryOrderService($arrRequest);
        return $objAction->execute();
    }
    
    public function UpdateOmsOrderInfoService($arrRequest)
    {
        $objAction = new Action_Service_UpdateOmsOrderInfoService($arrRequest);
        return $objAction->execute();
    }
}