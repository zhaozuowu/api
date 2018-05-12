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
        'Action_Service_SyncDriverInfo' => 'actions/service/SyncDriverInfo.php',
        'Action_Service_SyncRejectAllInfo' => 'actions/service/SyncRejectAllInfo.php',
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
     * 接收TMS事件司机信息，转发到货架（配车）
     * @param $arrRequest
     * @return mixed
     */
    public function syncDriverInfo($arrRequest) {
        $arrRequest = $arrRequest['objSyncDriverInfo'];
        $objAction = new Action_Service_SyncDriverInfo($arrRequest);
        return $objAction->execute();
    }

    /**
     * 接收TMS整单拒收信息，同步转发到货架
     * @param $arrRequest
     * @return mixed
     */
    public function syncRejectAllInfo($arrRequest) {
        $arrRequest = $arrRequest['objShipmentOrderRejectAllInfo'];
        $objAction = new Action_Service_SyncRejectAllInfo($arrRequest);
        return $objAction->execute();
    }
}
