<?php
/**
 * @name Service_Data_Shelf
 * @desc 货架相关逻辑
 * @author wende.chen@ele.me
 */
class Service_Data_Shelf
{
    /**
     * @var object
     */
    protected $objDaoWprcShelf;

    /**
     * init object
     */
    public function __construct()
    {
        $this->objDaoWprcShelf = new Dao_Wrpc_Shelf();
    }

    /**
     * 通知货架TMS司机信息（配车）
     * @param $strLogisticOrderId
     * @param $strDriverId
     * @param $strDriverName
     * @param $strDriverMobile
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function SyncDriverInfo($strLogisticOrderId, $strDriverId, $strDriverName, $strDriverMobile)
    {
        if (empty($strLogisticOrderId) || empty($strDriverId) || empty($strDriverName) || empty($strDriverMobile)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrRet = $this->objDaoWprcShelf->NotifyShelfShipmentDriverInfo($strLogisticOrderId, $strDriverId, $strDriverName, $strDriverMobile);
        if (empty($arrRet) || $arrRet['error_no'] !== 0) {
            Bd_Log::warning(sprintf("method[%s] failed sync tms driver info to shop logistic_order_id[%s]", __METHOD__, $strLogisticOrderId));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_DRIVER_INFO_FAIL);
        }
        return true;
    }

    /**
     * 通知货架TMS整单拒收信息
     * @param $strLogisticOrderId
     * @param $strRejectRemark
     * @param $strRejectInfo
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function SyncRejectAllInfo($strLogisticOrderId, $strRejectRemark, $strRejectInfo)
    {
        if (empty($strLogisticOrderId) || empty($strRejectRemark) || empty($strRejectInfo)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrRet = $this->objDaoWprcShelf->NotifyShelfShipmentOrderRejectAllInfo(
            $strLogisticOrderId,
            $strRejectRemark,
            $strRejectInfo);

        if (empty($arrRet) || $arrRet['error_no'] !== 0) {
            Bd_Log::warning(sprintf("method[%s] failed sync tms shipment order reject all logistic_order_id[%s]", __METHOD__, $strLogisticOrderId));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_SHIPMENT_REJECT_ALL_FAIL);
        }
        return true;
    }

    /**
     * 沧海nwms揽收后，通知货架揽收信息
     * @param $strLogisticOrderId
     * @param $strShipmentOrderId
     * @param $arrDriverInfo
     * @param $arrSkuInfo
     * @return bool
     * @throws Orderui_BusinessError
     */
    public function SyncAcceptStockinOrderSkuInfo(
        $strLogisticOrderId,
        $strShipmentOrderId,
        $arrDriverInfo,
        $arrSkuInfo)
    {
        if (empty($strLogisticOrderId)
            || empty($strShipmentOrderId)
            || empty($arrDriverInfo)
            || empty($arrSkuInfo)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }

        // 组织整理sku商品列表格式
        $arrSkuInfoList[] = [];
        foreach ($arrSkuInfo as $skuInfo) {
            $skuLine = [];
            $skuLine['skuId'] = $skuInfo['sku_id'];
            foreach ($skuInfo['distribute_info'] as $info) {
                $infoLine = [];
                $infoLine['expireDate'] = $info['expire_date'];
                $infoLine['amount'] = $info['amount'];
                $skuLine['distributeNumber'][] = $infoLine;
            }
            $arrSkuInfoList[] = $skuLine;
        }

        $arrRet = $this->objDaoWprcShelf->NotifyShelfSyncAcceptStockinOrderSkuInfo(
            $strLogisticOrderId,
            $strShipmentOrderId,
            $arrDriverInfo,
            $arrSkuInfoList);

        if (empty($arrRet) || $arrRet['error_no'] !== 0) {
            Bd_Log::warning(sprintf("method[%s] failed sync nwms stockin order accepted skus shipment_order_id[%s]", __METHOD__, $strShipmentOrderId));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_NWMS_ACCEPT_ORDER_SKUS_FAIL);
        }
        return true;
    }
}