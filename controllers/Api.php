<?php
/**
 * @name Controller_Api
 * @desc 订单模块Api Controller_Api
 * @author yu.jin03@ele.me 
 */
class Controller_Api extends Ap_Controller_Abstract {
    public $actions = array(
        'updateomsorderinfo' => 'actions/api/UpdateOmsOrderInfo.php', #增量更新子单信息#@skipped#
        'triggerevent' => 'actions/api/TriggerEvent.php', #增量更新子单信息#@skipped#
    );
}
