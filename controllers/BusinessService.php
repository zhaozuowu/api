<?php
/**
 * @name Controller_BusinessService
 * @desc Controller_BusinessService
 * @author yu.jin03@ele.me
 */
class Controller_BusinessService extends Orderui_Base_ServiceController
{
    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_CreateBusinessFormOrder' => 'actions/service/CreateBusinessFormOrder.php',
    ];

    /**
     * 创建业态订单
     * @param $arrRequest
     * @return array
     */
    public function createBusinessFormOrder($arrRequest) {
        $arrRequest = $arrRequest['objBusinessFormOrderInfo'];
        $objAction = new Action_Service_CreateBusinessFormOrder($arrRequest);
        return $objAction->execute();
    }
}