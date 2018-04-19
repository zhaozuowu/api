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
        'Action_Service_OutsideTriggerEvent' => 'actions/service/OutsideTriggerEvent.php',
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

    public function confirmStockinOrder($arrRequest)
    {
        return $this->outsideCall(__FUNCTION__, $arrRequest);
    }


    private function outsideCall($strMethodName, $arrRequest)
    {
        if (isset(Orderui_Define_OutsideEvent::OUTSIDE_EVENT_VALIDATE[$strMethodName])) {
            $arrRequest = [
                'event_key' => $strMethodName,
                'params' => $arrRequest['objData'],
            ];
            $objAction = new Action_Service_OutsideTriggerEvent($arrRequest);
            return $objAction->execute();
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    function __call($name, $arguments)
    {
        if (isset(Orderui_Define_OutsideEvent::OUTSIDE_EVENT_VALIDATE[$name])) {
            $arrRequest = [
                'event_key' => $name,
                'params' => $arguments[0],
            ];
            $objAction = new Action_Service_OutsideTriggerEvent($arrRequest);
            return $objAction->execute();
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
}
