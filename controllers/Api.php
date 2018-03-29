<?php
/**
 * @name Controller_Api
 * @desc 订单模块Api Controller_Api
 * @author yu.jin03@ele.me 
 */
class Controller_Api extends Ap_Controller_Abstract {
    public $actions = array(
        'updateomsorderinfo' => 'actions/api/UpdateOmsOrderInfo.php', #增量更新子单信息#@skipped#
         'deliveryorder' => 'actions/api/DeliveryOrder.php' #出库单揽收时接收产效期传给货架#@skipped#
    );
}
