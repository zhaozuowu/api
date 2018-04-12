<?php
/**
 * @name Event.php
 * @desc Event.php
 * @author yu.jin03@ele.me
 */

class Orderui_Event
{
    /**
     * 过滤签收事件
     * @param $arrSkuEvents
     * @return array
     */
    public static function filterEventTypes($arrSkuEvents) {
        if (empty($arrSkuEvents)) {
            return [];
        }
        $arrAdjustSkus = [];
        $arrPlanOffSkus = [];
        $arrOffSkus = [];
        $arrSignupSkus = [];
        foreach ((array)$arrSkuEvents as $arrSkuEventItem) {
            if (Orderui_Define_Const::BUSINESS_ORDER_EVENT_TYPE_ADJUST
                == $arrSkuEventItem['event_type']) {
                $arrAdjustSkus[$arrSkuEventItem['sku_id']] = $arrSkuEventItem['order_amount'];
            }
            if (Orderui_Define_Const::BUSINESS_ORDER_EVENT_TYPE_PLAN_PUTOFF
                == $arrSkuEventItem['event_type']) {
                $arrPlanOffSkus[$arrSkuEventItem['sku_id']] = $arrSkuEventItem['order_amount'];
            }
            if (Orderui_Define_Const::BUSINESS_ORDER_EVENT_TYPE_PUTOFF
                == $arrSkuEventItem['event_type']) {
                $arrOffSkus[$arrSkuEventItem['sku_id']] = $arrSkuEventItem['order_amount'];
            }
            if (Orderui_Define_Const::BUSINESS_ORDER_EVENT_TYPE_SIGNUP
                == $arrSkuEventItem['event_type']) {
                $arrSignupSkus[$arrSkuEventItem['sku_id']] = $arrSkuEventItem['order_amount'];
            }
        }
        return [
            'adjust_skus' => $arrAdjustSkus,
            'plan_off_skus' => $arrPlanOffSkus,
            'off_skus' => $arrOffSkus,
            'signup_skus' => $arrSignupSkus,
        ];
    }
}