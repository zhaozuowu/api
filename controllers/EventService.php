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

    private $arrEventConf = [
        'funcExample1' => [
            'client_id' => Orderui_Define_Event::CLIENT_NSCM,
            'event_key' => 'exampleKey',
        ],
    ];

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    function __call($name, $arguments)
    {
        if (isset($this->arrEventConf[$name])) {
            $arrRequest = [
                'client_id' => $this->arrEventConf[$name]['client_id'],
                'event_key' => $this->arrEventConf[$name]['event_key'],
                'data'      => $arguments[0]['objData'],
            ];
            $objAction = new Action_Service_TriggerEvent($arrRequest);
            return $objAction->execute();
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
}
