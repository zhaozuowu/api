<?php
/**
 * @name Controller_ShipmentService
 * @desc 签收物流单
 * @author  huabang.xue@ele.me
 */
class Controller_ShipmentService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_Signup'     => 'actions/service/Signup.php',
        'Action_Service_RejectShipmentOrder' => 'actions/service/RejectShipmentOrder.php',
    ];

    /**
     * 签收物流单
     * @param $arrRequest
     * @return array
     */
    public function signupShipmentOrder($arrRequest) {
        $arrRequest = $arrRequest['objSignupInfo'];
        $objAction = new Action_Service_Signup($arrRequest);
        return $objAction->execute();
    }

    /**
     * 拒收运单
     * @param $arrRequest
     * @return mixed
     */
    public function rejectShipmentOrder($arrRequest) {
        $arrRequest = $arrRequest['objShipmentOrderInfo'];
        $objAction = new Action_Service_RejectShipmentOrder($arrRequest);
        return $objAction->execute();
    }

    /**
     * 拒收运单
     * @param $arrRequest
     * @return mixed
     */
    public function rejectBusinessBackOrder($arrRequest) {
        $objAction = new Action_Service_RejectBusinessBackOrder($arrRequest);
        return $objAction->execute();
    }
}
