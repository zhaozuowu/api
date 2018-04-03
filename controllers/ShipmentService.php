<?php
/**
 * @name Controller_ShipmentService
 * @desc 签收运单
 * @author  huabang.xue@ele.me
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
        $objAction = new Action_Service_Signup($arrRequest);
        return $objAction->execute();
    }
}
