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
        Orderui_Define_OutsideEvent::EVENT_NAME_CONFIRM_STOCKIN_ORDER => [
            Orderui_Define_Event::BRANCH_1 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_TMS,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_TMS,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_TMS,
                'call' => 'confirmOrder',
                'format' => 'defaultFormat',
                'result' => 'defaultResult',
            ],
            Orderui_Define_Event::BRANCH_2 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_SHELF,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_SHELF,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_SHELF,
                'call' => 'confirmOrder',
                'format' => 'defaultFormat',
                'result' => 'defaultResult',
            ],
        ],
        Orderui_Define_OutsideEvent::EVENT_NAME_DELIVERY_ORDER_TMS => [
            Orderui_Define_Event::BRANCH_1 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_NWMS,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_NWMS,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_STOCKOUT,
                'call' => 'deliveryOrder',
                'format' => 'deliveryOrderFormatNwms',
                'result' => 'defaultJsonResult'
            ],
        ],
        Orderui_Define_OutsideEvent::EVENT_NAME_BATCH_PICKING_AMOUNT => [
            Orderui_Define_Event::BRANCH_1 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_TMS,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_TMS,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_TMS,
                'call' => 'pickingAmountForList',
                'format' => 'batchPickingAmount',
                'result' => 'defaultJsonResult'
            ],
            Orderui_Define_Event::BRANCH_2 => [
                'type' => Orderui_Define_Event::CALL_WRPC,
                'app_id' => Orderui_Define_Wrpc::APP_ID_SHELF,
                'namespace' => Orderui_Define_Wrpc::NAMESPACE_SHELF,
                'service' => Orderui_Define_Wrpc::SERVICE_NAME_SHELF,
                'call' => 'pickingAmountForList',
                'format' => 'batchPickingAmount',
                'result' => 'defaultJsonResult'
            ],
        ],
    ];
}