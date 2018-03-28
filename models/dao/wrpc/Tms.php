<?php
/**
 * @name Tms.php
 * @desc Tms.php
 * @author yu.jin03@ele.me
 */
class Dao_Wrpc_Tms
{
    /**
     * wrpc service
     * @var Bd_Wrpc_Client
     */
    protected $objWrpcService;

    /**
     * Dao_Wrpc_Tms constructor.
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::TMS_APP_ID,
            Orderui_Define_Wrpc::TMS_NAMESPACE,
            Orderui_Define_Wrpc::TMS_SERVICE_NAME);
    }

    /**
     * 取消运单
     * @param $intShipmentOrderId
     * @param $strRemark
     * @return array
     */
    public function cancelShipmentOrder($intShipmentOrderId, $strRemark)
    {
        $arrParams = $this->getCancelParams($intShipmentOrderId, $strRemark);
        return $this->objWrpcService->cancelForShelf($arrParams);
    }

    /**
     * 获取取消运单参数
     * @param $intShipmentOrderId
     * @param $strRemark
     */
    protected function getCancelParams($intShipmentOrderId, $strRemark)
    {
        $arrParams = [];
        $arrParams['user'] = (object)[];
        $arrParams['request'] = $this->getCancelRequest($intShipmentOrderId, $strRemark);
    }

    /**
     * 获取取消运单详细参数
     * @param $intShipmentOrderId
     * @param $strRemark
     * @return array
     */
    protected function getCancelRequest($intShipmentOrderId, $strRemark)
    {
        $arrCancelRequest = [];
        $arrCancelRequest['shipmentIds'][] = $intShipmentOrderId;
        $arrCancelRequest['reasonCode'] = 0;
        $arrCancelRequest['reasonLabel'] = '';
        $arrCancelRequest['reasonDesc'] = $strRemark;
        return $arrCancelRequest;
    }
}