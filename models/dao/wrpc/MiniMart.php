<?php
/**
 * @name MiniMart.php
 * @desc MiniMart.php
 * @author yu.jin03@ele.me
 */

class Dao_Wrpc_MiniMart
{
    /**
     * mini mart wrpc service
     * @var Bd_Wrpc_Client
     */
    protected $objWrpcMiniMart;

    /**
     * Dao_Wrpc_MiniMart constructor.
     */
    public function __construct()
    {
        $this->objWrpcMiniMart = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_SHELF,
            Orderui_Define_Wrpc::NAMESPACE_SHELF_BACKEND,
            Orderui_Define_Wrpc::SERVICE_NAME_SHELF_BACKEND);
    }

    /**
     * 通知货架运单创建结果
     * @param $intLogisticsOrderId
     * @param $intShipmentOrderId
     * @throws Orderui_BusinessError
     */
    public function notifyMiniMartRecallShipmentOrderCreate($intLogisticsOrderId, $intShipmentOrderId)
    {
        $arrParams = [];
        $arrParams['logisticsOrderCode'] = $intLogisticsOrderId;
        $arrParams['shipmentOrderCode'] = $intShipmentOrderId;
        $arrParams['success'] = empty($intShipmentOrderId) ? false : true;
        $arrParams = ['arg0' => $arrParams];
        $arrRet = $this->objWrpcMiniMart->recyclingCreate($arrParams);
        Bd_Log::trace(sprintf("method[%s] params[%s] ret[%s]",
                                __METHOD__, json_encode($arrParams), json_encode($arrRet)));
        if (!empty($arrRet['errno'])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_MINIMART_RECALL_SHELF_ORDER_FAILED);
            Bd_Log::warning(sprintf("method[%s] params[%s] ret[%s]",
                __METHOD__, json_encode($arrParams), json_encode($arrRet)));
        }
    }
}