<?php
/**
 * @name Controller_ShopService
 * @desc 门店相关service
 * @author  huabang.xue@ele.me
 */
class Controller_ShopService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_ShopSignup'     => 'actions/service/ShopSignup.php',
        'Action_Service_CreateShopReturnOrder' => 'actions/service/CreateShopReturnOrder.php',
    ];

    /**
     * 签收物流单
     * @param $arrRequest
     * @return array
     */
    public function signup($arrRequest) {
        $arrRequest = $arrRequest['objSignupInfo'];
        $objAction = new Action_Service_ShopSignup($arrRequest);
        return $objAction->execute();
    }

    /**
     * 拒收运单
     * @param $arrRequest
     * @return mixed
     */
    public function createShopReturnOrder($arrRequest) {
        $arrRequest = $arrRequest['objReturnOrderInfo'];
        $objAction = new Action_Service_CreateShopReturnOrder($arrRequest);
        return $objAction->execute();
    }
}
