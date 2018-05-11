<?php
/**
 * @name Controller_NwmsService
 * @desc 沧海调用部分
 * @author  wende.chen@ele.me
 */
class Controller_NwmsService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_SyncAcceptStockinOrderSkuInfo' => 'actions/service/SyncAcceptStockinOrderSkuInfo.php',
    ];

    /**
     * 接收NWMS揽收信息，转发货架
     * @param $arrRequest
     * @return mixed
     */
    public function syncAcceptStockinOrderSkuInfo($arrRequest) {
        $arrRequest = $arrRequest['objAcceptedSkuInfo'];
        $objAction = new Action_Service_SyncAcceptStockinOrderSkuInfo($arrRequest);
        return $objAction->execute();
    }
}
