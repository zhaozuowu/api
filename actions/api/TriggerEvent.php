<?php
/**
 * @name Action_TriggerEvent
 * @desc 接入api事件
 * @author huabang.xue@ele.me
 */

class Action_TriggerEvent extends Orderui_Base_ApiAction
{
    /**
     * input params
     * @var array
     */
    protected $arrInputParams = [
        'client_id' => 'int|required|min[1]|max[6]',
        'event_key' => 'str|required|len[256]',
        'data'      => 'json|decode|required',
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
     * add validate
     * @throws Wm_Error
     */
    function myExecute()
    {
        //校验系统与事件的对应关系是否合法
        if (!array_key_exists($this->arrFilterResult['client_id'], Orderui_Define_Event::CLIENT_LIST)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_CLIENT);
        }
        if (!array_key_exists($this->arrFilterResult['event_key'], Orderui_Define_Event::CLIENT_EVENT_LIST[$this->arrFilterResult['client_id']])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_EVENT);
        }
        //校验data参数格式
        $this->arrFilterResult['data'] = $this->validate(Orderui_Define_EventParameter::EVENT_PARAMETER_LIST[$this->arrFilterResult['event_key']], $this->arrFilterResult['data']);

        return parent::myExecute();
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