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
     * @param $strShipmentOrderId
     * @param $intDriverSex
     * @param $strDriverId
     * @param $strDriverName
     * @param $strDriverMobile
     * @return array
     * @throws Orderui_BusinessError
     */
    public function SyncDriverInfo($strShipmentOrderId, $intDriverSex, $strDriverId, $strDriverName, $strDriverMobile)
    {
        $boolIsSuccess = false;

        /** 门店不需要sex字段，因此不传入 **/

        $strLogisticOrderId = $this->GetLogisticOrderIdByShipmentOrderId($strShipmentOrderId);
        if (empty($strLogisticOrderId) || empty($strDriverId) || empty($strDriverName) || empty($strDriverMobile)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $arrRet = $this->objDaoWprcShelf->NotifyShelfShipmentDriverInfo($strShipmentOrderId, $strLogisticOrderId, $strDriverId, $strDriverName, $strDriverMobile);
        if (empty($arrRet) || $arrRet['errno'] !== 0) {
            Bd_Log::warning(sprintf("method[%s] failed sync tms driver info to shelf logistic_order_id[%s]", __METHOD__, $strLogisticOrderId));
             Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_DRIVER_INFO_FAIL);
            $boolIsSuccess = false;
        } else {
            $boolIsSuccess = true;
        }

        $result = [
            'shipment_order_id' => $strShipmentOrderId,
            'result' => $boolIsSuccess,
        ];
        return $result;
    }

    /**
     * 根据运单号查询对应的物流单号
     * @param $strShipmentOrderId
     * @return array
     * @throws Orderui_BusinessError
     */
    public function GetLogisticOrderIdByShipmentOrderId($strShipmentOrderId)
    {
        // 物流单号需要从运单查找出来进行转换
        // 从order_system_detail中拿到运单对应的business_form_order_id
        $strBusinessFormOrderId = Model_Orm_OrderSystemDetail::getBusinessOrderIdByShipmentId($strShipmentOrderId);
        if (empty($strBusinessFormOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_TMS_ORDER_NOT_FOUND);
        }

        // 再根据business_form_order_id去business_form_order中查找对应的物流单号（source_order_id）
        $strLogisticOrderId = Model_Orm_BusinessFormOrder::getBusinessFormOrderSourceOrderId($strBusinessFormOrderId);
        if (empty($strLogisticOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SOURCE_ORDER_NOT_FOUNT);
        }

        return $strLogisticOrderId;
    }

    /**
     * 通知货架TMS整单拒收信息
     * @param $strShipmentOrderId
     * @param $strRejectRemark
     * @param $strRejectInfo
     * @return array
     * @throws Orderui_BusinessError
     */
    public function SyncRejectAllInfo($strShipmentOrderId, $strRejectRemark, $strRejectInfo)
    {
        $boolIsSuccess = false;

        if (empty($strShipmentOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        $strLogisticOrderId = $this->GetLogisticOrderIdByShipmentOrderId($strShipmentOrderId);
        $arrRet = $this->objDaoWprcShelf->NotifyShelfShipmentOrderRejectAllInfo(
            $strLogisticOrderId,
            $strRejectRemark,
            $strRejectInfo);

        if (empty($arrRet) || $arrRet['errno'] !== 0) {
            Bd_Log::warning(sprintf("method[%s] failed sync tms shipment order reject all logistic_order_id[%s]", __METHOD__, $strLogisticOrderId));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOTIFY_SHELF_SHIPMENT_REJECT_ALL_FAIL);
            $boolIsSuccess = false;
        } else {
            $boolIsSuccess = true;
        }

        $result = [
            'shipment_order_id' => $strShipmentOrderId,
            'result' => $boolIsSuccess,
        ];

        return $result;
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