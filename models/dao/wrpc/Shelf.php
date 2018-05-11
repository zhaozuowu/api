<?php

/**
 * @name Dao_Wrpc_Shelf
 * @desc interact with shelf
 * @author chenwende@iwaimai.baidu.com
 */
class Dao_Wrpc_Shelf
{
    /**
     * Dao_Wrpc_Shelf constructor.
     * wrcp service
     * @var Bd_Wrpc_Client
     */
    private $objWrpcService;
    private $objWrpcServiceRecycle;

    /**
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_SHELF,
            Orderui_Define_Wrpc::NAMESPACE_SHELF,
            Orderui_Define_Wrpc::SERVICE_NAME_SHELF);

        $this->objWrpcServiceRecycle = new Bd_Wrpc_Client(Orderui_Define_Wrpc::APP_ID_SHELF,
            Orderui_Define_Wrpc::NAMESPACE_SHELF,
            Orderui_Define_Wrpc::SERVICE_NAME_SHELF_RECYCLE);
    }

    /**
     * 同步TMS司机信息到货架
     * @param $strLogisticOrderId
     * @param $strDriverId
     * @param $strDriverName
     * @param $strDriverMobile
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function NotifyShelfShipmentDriverInfo($strLogisticOrderId, $strDriverId, $strDriverName, $strDriverMobile)
    {
        $arrParams = [];
        $arrParams['logisticsOrderCode'] = $strLogisticOrderId;
        $arrParams['driverInfo'] = [
            'executorId' => $strDriverId,
            'executorName' => $strDriverName,
            'executorPhone' => $strDriverMobile,
        ];

        $arrRet = $this->objWrpcServiceRecycle->updateDistributeNumber($arrParams);
        Bd_Log::trace(sprintf("method[%s] call shelf service update shipment driver info [%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s]", __METHOD__, json_encode($arrRet)));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_DRIVER_INFO_FAIL);
        }
        return $arrRet;
    }

    /**
     * 同步TMS整单拒收到货架
     * @param $strLogisticOrderId
     * @param $strRejectRemark
     * @param $strRejectInfo
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function NotifyShelfShipmentOrderRejectAllInfo($strLogisticOrderId, $strRejectRemark, $strRejectInfo)
    {
        $arrParams = [];
        $arrParams['RevokeLogisticsOrderRequestDto'] = [
            'logisticsOrderCode' => $strLogisticOrderId,
            'rejectRemark' => $strRejectRemark,
            'rejectInfo' => $strRejectInfo,
        ];

        $arrRet = $this->objWrpcServiceRecycle->rejectRevokeLogisticsOrder($arrParams);
        Bd_Log::trace(sprintf("method[%s] call shelf service update shipment order reject all [%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s]", __METHOD__, json_encode($arrRet)));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_SHIPMENT_REJECT_ALL_FAIL);
        }
        return $arrRet;
    }

    /**
     * 通知货架揽收信息
     * @param $strLogisticOrderId
     * @param $strShipmentOrderId
     * @param $arrDriverInfo
     * @param $arrSkuInfo
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function NotifyShelfSyncAcceptStockinOrderSkuInfo(
        $strLogisticOrderId,
        $strShipmentOrderId,
        $arrDriverInfo,
        $arrSkuInfo)
    {
        $arrParams = [];
        $arrParams['logisticsOrderCode'] = $strLogisticOrderId;
        $arrParams['shipmentOrderCode'] = $strShipmentOrderId;
        $arrParams['driverInfo'] = $arrDriverInfo;
        $arrParams['skuList'] = $arrSkuInfo;
        $arrRet = $this->objWrpcService->shelfSyncAcceptStockinOrderSkuInfo($arrParams);
        Bd_Log::trace(sprintf("method[%s] call shelf service update nwms accepted skus [%s]", __METHOD__, json_encode($arrRet)));
        if (0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s]", __METHOD__, json_encode($arrRet)));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_NWMS_ACCEPT_ORDER_SKUS_FAIL);
        }
        return $arrRet;
    }
}