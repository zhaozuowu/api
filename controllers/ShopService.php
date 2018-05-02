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
        'Action_Service_RejectShipmentOrder' => 'actions/service/RejectShipmentOrder.php',
        'Action_Service_UpdateStockInOrderSkuPlanAmount' => 'actions/service/UpdateStockInOrderSkuPlanAmount.php',
        'Action_Service_UpdateStockoutOrderSkuPickupInfo' => 'actions/service/UpdateStockoutOrderSkuPickupInfo.php',
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

    /**
     * OMS退货修正销退入库单计划入库数
     * @param $arrRequest
     * @return mixed
     */
    public function updateStockInOrderSkuPlanAmount($arrRequest) {
        $arrRequest = $arrRequest['objStockinPlanInAmountInfo'];
        $objAction = new Action_Service_UpdateStockInOrderSkuPlanAmount($arrRequest);
        return $objAction->execute();
    }

    /**
     * OMS接收NWMS出库单拣货信息接口
     * NWMS调用此OMS接口，OMS通知门店修改出库商品数量
     * @param $arrRequest
     * @return mixed
     */
    public function updateStockoutOrderSkuPickupInfo($arrRequest) {
        $arrRequest = $arrRequest['objStockoutPickupAmountInfo'];
        $objAction = new Action_Service_UpdateStockoutOrderSkuPickupInfo($arrRequest);
        return $objAction->execute();
    }
}
