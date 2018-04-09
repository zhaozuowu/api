<?php
/**
 * @name Controller_EventService
 * @desc 接入service事件
 * @author  huabang.xue@ele.me
 */
class Controller_EventService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_TriggerEvent' => 'actions/service/TriggerEvent.php',
    ];

    /**
     * 创建业态订单
     * @param $arrRequest
     * @return array
     */
    public function triggerEvent($arrRequest) {
        $arrRequest = $arrRequest['objEventInfo'];
        $objAction = new Action_Service_TriggerEvent($arrRequest);
        return $objAction->execute();
    }
}
