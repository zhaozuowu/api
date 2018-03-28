<?php
/**
 * @name Controller_ShipmentService
 * @desc 签收运单
 * @author  jinyu02@iwaimai.baidu.com
 */
class Controller_ShipmentService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_SignupShipmentOrder'     => 'actions/service/SignupShipmentOrder.php',
    ];

    /**
     * 签收运单
     * @param $arrRequest
     * @return array
     */
    public function signupShipmentOrder($arrRequest) {
        $arrRequest = $arrRequest['objShipmentOrderInfo'];
        $objAction = new Action_Service_SignupShipmentOrder($arrRequest);
        return $objAction->execute();
    }
}
