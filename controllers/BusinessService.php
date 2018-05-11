<?php
/**
 * @name Controller_BusinessService
 * @desc 创建业态订单
 * @author  jinyu02@iwaimai.baidu.com
 */
class Controller_BusinessService extends Orderui_Base_ServiceController {

    /**
     * 地址映射
     * @var array
     */
    public $arrMap = [
        'Action_Service_CreateBusinessFormOrder' => 'actions/service/CreateBusinessFormOrder.php',
        'Action_Service_CancelLogisticsOrder' => 'actions/service/CancelLogisticsOrder.php',
        'Action_Service_RecallShelf' => 'actions/service/RecallShelf.php',
    ];

    /**
     * 创建业态订单
     * @param $arrRequest
     * @return array
     */
    public function createBusinessFormOrder($arrRequest)
    {
        $arrRequest = $arrRequest['objBusinessFormOrderInfo'];
        $objAction = new Action_Service_CreateBusinessFormOrder($arrRequest);
        return $objAction->execute();
    }

    /**
     * 取消物流单
     * @param $strLogisticsOrderId
     * @param $strCancelRemark
     * @return array
     */
    public function cancelLogisticsOrder($arrRequest)
    {
        $objAction = new Action_Service_CancelLogisticsOrder($arrRequest);
        return $objAction->execute();
    }

    /**
     * 货架撤点
     * @param $arrRequest
     * @return array
     */
    public function recallShelf($arrRequest)
    {
        $arrRequest = $arrRequest['shelf_recallorder_info'];
        $objAction = new Action_Service_RecallShelf($arrRequest);
        return $objAction->execute();
    }
}
