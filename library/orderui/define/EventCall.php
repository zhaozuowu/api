<?php
/**
 * @name Orderui_Define_EventCall
 * @desc Orderui_Define_EventCall
 * @author bochao.lv@ele.me
 */

class Orderui_Define_EventCall
{
    /**
     * branch array
     */
    const BRANCH = [
        Orderui_Define_Event::EVENT_SIGNUP_SHIPMENT_ORDER => [
            Orderui_Define_Event::BRANCH_1 => [
                'type' => Orderui_Define_Event::CALL_RAL,
                'service' => Orderui_Define_Service::SERVICE_NWMS_ORDER,
                'url' => '/order/stockout/finishorder',
                'format' => 'formatNwmsFinishOrder',
                'result' => 'defaultResult',
            ],
            Orderui_Define_Event::BRANCH_2 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_TMS,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_TMS,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_TMS,
                'call' => 'tmsFunction',
                'format' => 'createBusinessFormOrder',
                'result' => 'defaultResult',
            ],
        ],
    ];
}