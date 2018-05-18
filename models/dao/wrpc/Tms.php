<?php
/**
 * @name Dao_Wrpc_Tms
 * @desc interact with tms
 * @author jinyu02@iwaimai.baidu.com
 */
class Dao_Wrpc_Tms
{
    /**
     * Dao_Wrpc_Tms constructor.
     * wrcp service
     * @var Bd_Wrpc_Client
     */
    private $objWrpcService;

    /**
     * @param $strNamespace
     * @param $strServiceName
     * init
     */
    public function __construct(
        $strNamespace = Orderui_Define_Wrpc::NAMESPACE_TMS_REFER_WMS,
        $strServiceName = Orderui_Define_Wrpc::SERVICE_NAME_TMS_REFER_WMS)
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::TMS_APP_ID, $strNamespace, $strServiceName);
    }

    /**
     * 创建tms运单
     * @param $arrInput
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function createShipmentOrder($arrInput)
    {
        $strRoutingKey = sprintf("loc=%s", $arrInput['warehouse_location']);
        $this->objWrpcService->setMeta(["routing-key"=>$strRoutingKey]);
        $arrParams = $this->getCreateShipmentParams($arrInput);
        $arrRet = $this->objWrpcService->processWarehouseRequest($arrParams);
        Bd_Log::trace(sprintf("method[%s] params[%s] processWarehouseRequest[%s]",
            __METHOD__, json_encode($arrParams), json_encode($arrRet)));
        if (empty($arrRet['data']) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_RECALL_SHELF_CREATE_SHIPMENT_ORDER_FAILED);
        }
        return $arrRet['data'];
    }

    /**
     * 获取运单创建参数
     * @param array $arrInput
     * @return array
     */
    protected function getCreateShipmentParams($arrInput) {
        $arrParams = [];
        $arrParams['user'] = (object)[];
        $arrParams['warehouseRequest'] = $this->getWarehouseRequest($arrInput);
        return $arrParams;
    }

    /**
     * 拼接创建运单参数
     * @param array $arrInput
     * @return array
     */
    protected function getWarehouseRequest($arrInput) {
        $arrWarehouseRequest = [];
        $arrShelfSkuList = $arrInput['new_shelf_info'];
        $arrExpectArriveTime = $arrInput['expect_arrive_time'];
        $arrWarehouseRequest['warehouseId'] = empty($arrInput['warehouse_id']) ? '' : intval($arrInput['warehouse_id']);
        $arrWarehouseRequest['businessType'] = empty($arrInput['business_form_order_type']) ? 0 : strval($arrInput['business_form_order_type']);
        $arrWarehouseRequest['businessSubType'] = empty($arrInput['order_supply_type']) ? 0 : intval($arrInput['order_supply_type']);
        $arrWarehouseRequest['businessJson'] = json_encode($arrShelfSkuList);
        $arrWarehouseRequest['orderRemark'] = empty($arrInput['business_form_order_remark']) ? '' : strval($arrInput['business_form_order_remark']);
        $arrWarehouseRequest['stockoutNumber'] = empty($arrInput['stockout_order_id']) ? 0 : intval($arrInput['stockout_order_id']);
        $arrWarehouseRequest['orderNumber'] = empty($arrInput['logistics_order_id']) ? 0 : intval($arrInput['logistics_order_id']);
        $arrWarehouseRequest['requireReceiveStartTime'] = empty($arrExpectArriveTime['start']) ? 0 : $arrExpectArriveTime['start'];
        $arrWarehouseRequest['requireReceiveEndTime'] = empty($arrExpectArriveTime['end']) ? 0 : $arrExpectArriveTime['end'];
        $arrWarehouseRequest['products'] = $this->getProducts($arrInput['skus']);
        $arrWarehouseRequest['userInfo'] = $this->getUserInfo($arrInput['customer_info']);
        $arrWarehouseRequest['backType'] = $arrInput['back_type'];
        $arrWarehouseRequest['warehouseId'] = $arrInput['warehouse_id'];
        if (!empty($arrInput['orderTime'])) {
            $arrWarehouseRequest['orderTime'] = $arrInput['orderTime'];
        }
        return $arrWarehouseRequest;
    }

    /**
     * 拼接商品信息参数
     * @param $arrSkus
     * @return array
     */
    protected function getProducts($arrSkus) {
        $arrProduts = [];
        if (empty($arrSkus)) {
            return $arrProduts;
        }
        foreach ((array)$arrSkus as $arrSkuItem) {
            $arrProdutItem = [];
            $arrProdutItem['skuId'] = empty($arrSkuItem['sku_id']) ? 0 : $arrSkuItem['sku_id'];
            $arrProdutItem['name'] = empty($arrSkuItem['sku_name']) ? '' : $arrSkuItem['sku_name'];
            $arrProdutItem['amount'] = empty($arrSkuItem['return_amount']) ? 0 : $arrSkuItem['return_amount'];
            $arrProdutItem['netWeight'] = empty($arrSkuItem['sku_net']) ? '' : intval($arrSkuItem['sku_net']);
            $arrProdutItem['netWeightUnit'] = empty($arrSkuItem['sku_net_unit']) ? 0 : intval($arrSkuItem['sku_net_unit']);
            $arrProdutItem['upcUnit'] = empty($arrSkuItem['upc_unit']) ? 0 : intval($arrSkuItem['upc_unit']);
            $arrProdutItem['specifications'] = empty($arrSkuItem['upc_unit_num']) ? 0 : intval($arrSkuItem['upc_unit_num']);
            $arrProdutItem['eventType'] = empty($arrSkuItem['event_type']) ? 0 : intval($arrSkuItem['event_type']);
            $arrProduts[] = $arrProdutItem;
        }
        return $arrProduts;
    }

    /**
     * 拼接客户信息参数
     * @param $arrInput
     * @return array
     */
    protected function getUserInfo($arrInput) {
        $arrUserInfo = [];
        if (empty($arrInput)) {
            return [];
        }
        $arrUserInfo['npName'] = empty($arrInput['name']) ? '' : strval($arrInput['name']);
        $arrUserInfo['npId'] = empty($arrInput['id']) ? 0 : strval($arrInput['id']);
        $arrUserInfo['contactName'] = empty($arrInput['contactor']) ? '' : strval($arrInput['contactor']);
        $arrUserInfo['contactPhone'] = empty($arrInput['contact']) ? '' : strval($arrInput['contact']);
        $arrUserInfo['customerServiceName'] = empty($arrInput['executor']) ? '' : strval($arrInput['executor']);
        $arrUserInfo['customerServicePhone'] = empty($arrInput['executor_contact']) ? '' : strval($arrInput['executor_contact']);
        $arrUserInfo['poi'] = (object)$this->getPoi($arrInput);
        return $arrUserInfo;
    }

    /**
     * 拼接客户坐标信息
     * @param $arrInput
     * @return array
     */
    protected function getPoi($arrInput) {
        $arrPoiInfo = [];
        if (empty($arrInput)) {
            return [];
        }
        $arrLocation = explode(',', $arrInput['location']);
        $arrPoiInfo['longitude'] = empty($arrLocation[0]) ? 0 : floatval($arrLocation[0]);
        $arrPoiInfo['latitude'] = empty($arrLocation[1]) ? 0 : floatval($arrLocation[1]);
        $arrPoiInfo['address'] = empty($arrInput['address']) ? '' : strval($arrInput['address']);
        $arrPoiInfo['areaCode'] = empty($arrInput['region_id']) ? '' : strval($arrInput['region_id']);
        $arrPoiInfo['cityId'] = empty($arrInput['city_id']) ? 0 : intval($arrInput['city_id']);
        $arrPoiInfo['cityName'] = empty($arrInput['city_name']) ? '' : strval($arrInput['city_name']);
        $arrPoiInfo['districtId'] = empty($arrInput['region_id']) ? 0 : intval($arrInput['region_id']);
        $arrPoiInfo['districtName'] = empty($arrInput['region_name']) ? '' : strval($arrInput['region_name']);
        $arrPoiInfo['coordsType'] = empty($arrInput['location_source']) ? 0 : intval($arrInput['location_source']);
        return $arrPoiInfo;
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
        $arrParams['request'] = $this->getCancelRequest($intShipmentOrderId, $strRemark);
        return $arrParams;
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

    /**
     * 调用tms签收
     * @param $intShipmentOrderId
     * @param $intBizType
     * @param $arrSkus
     * @param $arrUser
     * @return mixed
     */
    public function signupShipmentOrder($intShipmentOrderId, $intBizType, $arrSkus)
    {
        $strRoutingKey = sprintf("shardid=%d", $intShipmentOrderId%100);
        $this->objWrpcService->setMeta(['routing-key'=>$strRoutingKey]);
        $arrParams = $this->getSignupParams($intShipmentOrderId, $intBizType, $arrSkus);
        return $this->objWrpcService->signUp($arrParams);
    }

    /**
     * 拼接签收参数
     * @param $intShipmentOrderId
     * @param $intBizType
     * @param $arrSkus
     * @param $arrUser
     * @return array
     */
    public function getSignupParams($intShipmentOrderId, $intBizType, $arrSkus)
    {
        $arrParams = [];
        $arrSignupRequest = [];
        $arrSignupRequest['shipmentId'] = $intShipmentOrderId;
        $arrSignupRequest['bizType'] = $intBizType;
        $arrSignupRequest['skus'] = $this->getSkus($arrSkus);
        $arrParams['shipmentId'] = $intShipmentOrderId;
        $arrParams['request'] = $arrSignupRequest;
        $arrParams['user'] = (object)[];
        return $arrParams;
    }

    /**
     * 拼接skus参数
     * @param $arrSkus
     * @return array
     */
    public function getSkus($arrSkus) {
        $arrRetSkus = [];
        if (empty($arrSkus)) {
            return [];
        }
        foreach ((array)$arrSkus as $intKey => $intCount) {
            $arrRetSkuItem = [];
            $arrRetSkuItem['id'] = $intKey;
            $arrRetSkuItem['count'] = $intCount;
            $arrRetSkus[] = $arrRetSkuItem;
        }
        return $arrRetSkus;
    }

    /**
     * 批量创建运单
     * @param $arrParams
     * @return array
     */
    public function createBatchShipmentOrders($arrParams) {
        return [12202202,122,3,2,3];
    }

    /**
     * 撤点订单盘点通知TMS
     * @param $intShipmentOrderId
     * @param $strWarehouseLocation
     * @param $arrBusinessInfo
     * @param $arrSkuList
     * @return mixed
     * @throws Orderui_BusinessError
     */
    public function backickingAmount($intShipmentOrderId, $strWarehouseLocation, $arrBusinessInfo, $arrSkuList)
    {
        $strRoutingKey = sprintf("loc=%s", $strWarehouseLocation);
        $this->objWrpcService->setMeta(["routing-key"=>$strRoutingKey]);
        $arrParams['backReceiptProductsInfo'] = [
            'shipmentId' => $intShipmentOrderId,
            'receiptProducts' => $arrSkuList,
            'businessJson' => json_encode($arrBusinessInfo),
        ];
        $arrRet = $this->objWrpcService->backickingAmount($arrParams);
        Bd_Log::trace(sprintf("method[%s] params[%s] backickingAmount[%s]",
            __METHOD__, json_encode($arrParams), json_encode($arrRet)));
        if (empty($arrRet['data']) || 0 != $arrRet['errno']) {
            Bd_Log::warning(sprintf("method[%s] arrRet[%s] routing-key[%s]",
                __METHOD__, json_encode($arrRet), $strRoutingKey));
            Orderui_BusinessError::throwException(Orderui_Error_Code::BACK_ORDER_NOTIFY_TMS_FAIL);
        }
        return $arrRet['data'];

    }
}