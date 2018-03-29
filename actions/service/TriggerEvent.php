<?php
/**
 * @name Action_Service_TriggerEvent
 * @desc 接入service事件
 * @author huabang.xue@ele.me
 */

class Action_Service_TriggerEvent extends Orderui_Base_ServiceAction
{
    //是否接入事件
    protected $boolIsEvent = true;
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'client_id' => 'int|required|min[1]|max[6]',
        'event_key' => 'str|required|len[256]',
        'data'      => 'json|required|decode',
    ];

    /**
     * 请求方式post
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * @return mixed|void
     */
    function myConstruct()
    {
        $this->objPage = new Service_Page_TriggerEvent();
    }

    /**
     * @param array $data
     * @return array
     */
    public function format($data)
    {
        return $data;
    }
}