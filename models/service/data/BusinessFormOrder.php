<?php
/**
 * @name Service_Data_BusinessFormOrder
 * @desc Service_Data_BusinessFormOrder
 * @author yu.jin03@ele.me
 */
class Service_Data_BusinessFormOrder
{
    /**
     * @var Dao_Ral_Sku
     */
    protected $objDaoRalSku;

    /**
     * @var Dao_Ral_NWmsOrder
     */
    protected $objDaoRalNWmsOrder;

    /**
     * @var Dao_Ral_Warehouse
     */
    protected $objDaoRalWarehouse;

    /**
     * @var Dao_Wrpc_Tms
     */
    protected $objDaoWrpcTms;

    /**
     * @var Dao_Redis_BusinessOrder
     */
    protected $objDaoRedisBsOrder;

    /**
     * init object
     */
    public function __construct()
    {
        $this->objDaoRalSku = new Dao_Ral_Sku();
        $this->objDaoRalNWmsOrder = new Dao_Ral_NWmsOrder();
        $this->objDaoWrpcTms = new Dao_Wrpc_Tms();
        $this->objDaoRalWarehouse = new Dao_Ral_Warehouse();
        $this->objDaoRedisBsOrder = new Dao_Redis_BusinessOrder();
    }

    /**
     * 授权介入校验
     * @param $strBusinessFormKey
     * @param $strBusinessFormToken
     * @return void
     * @throws Orderui_BusinessError
     */
    public function checkAuthority($strBusinessFormKey, $strBusinessFormToken) {
        $strGenKey = md5($strBusinessFormKey . Orderui_Define_BusinessFormOrder::SALT_VAL);
        if ($strGenKey != $strBusinessFormToken) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_CHECK_AUTHORITY_ERROR);
        }
    }

    /**
     * 创建业态订单
     * @param $arrInput
     * @return void
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function createBusinessFormOrder($arrInput) {
        $this->checkAuthority($arrInput['business_form_key'], $arrInput['business_form_token']);
        $arrCreateParams = $this->getCreateParams($arrInput);
        $arrBatchSkuParams = $this->getBatchSkuParams($arrCreateParams['business_form_order_id'], $arrInput['skus']);
        Model_Orm_BusinessFormOrder::getConnection()->transaction(function ()
                                        use ($arrCreateParams, $arrBatchSkuParams) {
            $objOrmBusinessFormOrder = new Model_Orm_BusinessFormOrder();
            $objOrmBusinessFormOrder->create($arrCreateParams);
            Model_Orm_BusinessFormOrderSku::batchInsert($arrBatchSkuParams);
        });
    }

    /**
     * 创建oms订单以子单
     * @param $arrBusinessFormOrderInfo
     * @return array
     * @throws Exception
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function createOrder($arrBusinessFormOrderInfo)
    {
        $arrBusinessFormOrderInfo['business_form_order_id'] = Orderui_Util_Utility::generateBusinessFormOrderId();
        //进行拆单处理
        $arrOrderSysDetailList = $this->splitBusinessOrder($arrBusinessFormOrderInfo);
        $arrNwmsResponseList = $this->distributeOrder($arrOrderSysDetailList);
        //校验是否已经创建
        $boolWhetherExisted = $this->checkBusinessFormOrderWhetherExisted($arrBusinessFormOrderInfo['logistics_order_id']);
        if ($boolWhetherExisted) {
            return $arrNwmsResponseList;
        }
        //拼接oms创建需要的参数
        list($arrBusinessFormOrderInfo, $arrOrderSysListDb, $arrOrderSysDetailListDb) =
            Orderui_Lib_BusinessFormOrder::formatBusinessInfo($arrNwmsResponseList, $arrBusinessFormOrderInfo);
        $arrBusinessFormOrderDb = $this->assembleBusinessFormOrder($arrBusinessFormOrderInfo);
        $intBusinessCreateStatus = $arrBusinessFormOrderInfo['business_form_order_create_status'];
        //创建oms订单及子单
        Model_Orm_BusinessFormOrder::getConnection()->transaction(function () use ($arrOrderSysListDb,
                                        $arrOrderSysDetailListDb, $arrBusinessFormOrderDb, $intBusinessCreateStatus) {
            Model_Orm_BusinessFormOrder::insert($arrBusinessFormOrderDb['order_info']);
            Model_Orm_BusinessFormOrderSku::batchInsert($arrBusinessFormOrderDb['sku_info']);
            if (Orderui_Define_Const::NWMS_ORDER_CREATE_STATUS_SUCCESS == $intBusinessCreateStatus) {
                Model_Orm_OrderSystem::batchInsert($arrOrderSysListDb);
            }
            if (Orderui_Define_Const::NWMS_ORDER_CREATE_STATUS_SUCCESS == $intBusinessCreateStatus) {
                Model_Orm_OrderSystemDetail::batchInsert($arrOrderSysDetailListDb['detail_list']);
                Model_Orm_OrderSystemDetailSku::batchInsert($arrOrderSysDetailListDb['sku_list']);
            }
        });
        if (Orderui_Define_Const::NWMS_ORDER_CREATE_STATUS_FAILED == $intBusinessCreateStatus) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR);
        }
        return $arrNwmsResponseList;
    }

    /**
     * 组建业态订单信息
     * @param  array $arrBusinessOrderInfo
     * @return array
     * @throws Nscm_Exception_Error
     * @throws Wm_Error
     */
    public function assembleBusinessFormOrder($arrBusinessOrderInfo)
    {
        $arrCreateParams = $this->getCreateParams($arrBusinessOrderInfo);
        $arrBatchSkuParams = $this->getBatchSkuParams($arrCreateParams['business_form_order_id'], $arrBusinessOrderInfo['skus']);
        return [
            'order_info' => $arrCreateParams,
            'sku_info' => $arrBatchSkuParams,
        ];
    }
    /**
     * 业态订单参数校验
     * @param $arrInput
     * @return void
     * @throws Orderui_BusinessError
     */
    public function checkCreateParams($arrInput) {
        if ($arrInput['business_form_order_type']
            && !isset(Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_LIST[$arrInput['business_form_order_type']])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_BUSINESS_FORM_ORDER_TYPE_ERROR);
        }
        //无人货架信息校验
        $arrShelfInfo = $arrInput['shelf_info'];
        if (empty($arrShelfInfo)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_SKU_BUSINESS_SHELF_INFO_ERROR);
        }
        if (!isset(Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE[$arrShelfInfo['supply_type']])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_SKU_BUSINESS_SHELF_INFO_ERROR);
        }
        if (Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_CREATE == $arrShelfInfo['supply_type']
            && empty($arrShelfInfo['devices'])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_SHELF_ERROR);
        }
        foreach ((array)$arrShelfInfo['devices'] as $intKey => $intAmount) {
            $intKey = intval($intKey);
            $intAmount = intval($intAmount);
            if (!isset(Orderui_Define_BusinessFormOrder::ORDER_DEVICE_MAP[$intKey])
                || $intAmount <= 0) {
                Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_SHELF_ERROR);
            }
        }
        $arrShelfInfo['devices'] = (object)$arrShelfInfo;
        if (count(json_encode($arrShelfInfo)) > 128) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR);
        }
        //校验预计送达时间
        $arrExpectArriveTime = $arrInput['expect_arrive_time'];
        $intCurTime = time();
        if ($arrExpectArriveTime['start'] < $intCurTime ||
            $arrExpectArriveTime['end'] <= $arrExpectArriveTime['start']) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_EXPECT_ARRIVE_TIME_ERROR);
        }
        //校验位置信息
        $arrLocation = explode(',', $arrInput['customer_location']);
        if (floatval($arrLocation[1]) >= Orderui_Define_BusinessFormOrder::MAX_LATITUDE
            || floatval($arrLocation[1]) <= Orderui_Define_BusinessFormOrder::MIN_LATITUDE) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_LATITUDE_ERROR);
        }
        if (floatval($arrLocation[0]) >= Orderui_Define_BusinessFormOrder::MAX_LONGITUDE
            || floatval($arrLocation[0]) <= Orderui_Define_BusinessFormOrder::MIN_LONGITUDE) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_STOCKOUT_LONGITUDE_ERROR);
        }
        if (!isset(Order_Define_BusinessFormOrder::CUSTOMER_LOCATION_SOURCE_TYPE[$arrInput['customer_location_source']])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CUSTOMER_LOCATION_SOURCE_ERROR);
        }
    }

    /**
     * 拼接业态订单创建参数
     * @param $arrInput
     * @return array
     * @throws Wm_Error
     */
    protected function getCreateParams($arrInput) {
        $arrCreateParams = [];
        if (empty($arrInput)) {
            return $arrCreateParams;
        }
        $arrCreateParams['business_form_order_id'] = empty($arrInput['business_form_order_id']) ?
                                                        Orderui_Util_Utility::generateBusinessFormOrderId() : $arrInput['business_form_order_id'];
        $arrCreateParams['source_order_id'] = empty($arrInput['logistics_order_id']) ?
                                                        0 : intval($arrInput['logistics_order_id']);
        $arrCreateParams['business_form_order_type'] = empty($arrInput['business_form_order_type']) ?
                                                        0 : intval($arrInput['business_form_order_type']);
        $arrCreateParams['customer_city_id'] = empty($arrInput['customer_city_id']) ?
                                                        0 : intval($arrInput['customer_city_id']);
        $arrCreateParams['customer_city_name'] = empty($arrInput['customer_city_name']) ?
            '' : strval($arrInput['customer_city_name']);
        $arrCreateParams['customer_id'] = empty($arrInput['customer_id']) ?
                                                        '' : strval($arrInput['customer_id']);
        $arrCreateParams['customer_name'] = empty($arrInput['customer_name']) ?
                                                        '' : strval($arrInput['customer_name']);
        $arrCreateParams['customer_contactor'] = empty($arrInput['customer_contactor']) ?
                                                        '' : strval($arrInput['customer_contactor']);
        $arrCreateParams['customer_contact'] = empty($arrInput['customer_contact']) ?
                                                        '' : strval($arrInput['customer_contact']);
        $arrCreateParams['customer_address'] = empty($arrInput['customer_address']) ?
                                                        '' : strval($arrInput['customer_address']);
        $arrCreateParams['business_form_ext'] = json_encode($this->getBusinessFormExt($arrInput));
        $arrCreateParams['supply_type']       = intval($arrInput['shelf_info']['supply_type']);
        $arrCreateParams['business_form_order_exception'] = empty($arrInput['business_form_order_exception']) ?
                                                        '' : strval($arrInput['business_form_order_exception']);
        $arrCreateParams['process_time'] = Orderui_Util::getNowUnixDateTime();
        $arrCreateParams['status'] = $arrInput['business_form_order_create_status'];
        return $arrCreateParams;
    }

    /**
     * 获取业态订单扩展字段信息
     * @param $arrInput
     * @return array
     */
    protected function getBusinessFormExt($arrInput) {
        $arrBusinessFormExt = [];
        if (empty($arrInput)) {
            return $arrBusinessFormExt;
        }
        $arrBusinessFormExt = $arrInput['shelf_info'];
        $arrBusinessFormExt['customer_location'] = empty($arrInput['customer_location']) ?
                                                    '' : strval($arrInput['customer_location']);
        $arrBusinessFormExt['customer_location_source'] = empty($arrInput['customer_location_source']) ?
                                                    0 : intval($arrInput['customer_location_source']);
        $arrBusinessFormExt['executor'] = empty($arrInput['executor']) ?
                                                    0 : strval($arrInput['executor']);
        $arrBusinessFormExt['executor_contact'] = empty($arrInput['executor_contact']) ?
                                                    '' : strval($arrInput['executor_contact']);
        $arrBusinessFormExt['expect_arrive_start_time'] = empty($arrInput['expect_arrive_time']['start']) ?
            '' : intval($arrInput['expect_arrive_time']['start']);
        $arrBusinessFormExt['expect_arrive_end_time'] = empty($arrInput['expect_arrive_time']['end']) ?
            '' : intval($arrInput['expect_arrive_time']['end']);

        $arrBusinessFormExt['skus_event'] = empty($arrInput['skus_event']) ?
            [] : ($arrInput['skus_event']);
        return$arrBusinessFormExt;
    }

    /**
     * 获取业态订单商品信息
     * @param integer $intBusinessFormOrderId
     * @param array $arrSkus
     * @return array
     * @throws Nscm_Exception_Error
     */
    protected function getBatchSkuParams($intBusinessFormOrderId, $arrSkus) {
        $arrSkuParams = [];
        if (empty($arrSkus)) {
            return $arrSkuParams;
        }
        $arrSkus = $this->appendSkuNameToSkus($arrSkus);
        foreach ((array)$arrSkus as $arrSkuItem) {
            $arrSkuParamsItem = [];
            $arrSkuParamsItem['business_form_order_id'] = $intBusinessFormOrderId;
            $arrSkuParamsItem['sku_id'] = intval($arrSkuItem['sku_id']);
            $arrSkuParamsItem['sku_name'] = strval($arrSkuItem['sku_name']);
            $arrSkuParamsItem['sku_amount'] = intval($arrSkuItem['order_amount']);
            $arrSkuParamsItem['sku_exception_time'] = isset($arrSkuItem['exception_time']) ?
                                                    $arrSkuItem['exception_time'] : 0;
            $arrSkuParamsItem['sku_exception'] = isset($arrSkuItem['exception_info']) ?
                                                    $arrSkuItem['exception_info'] : '';
            $arrSkuParamsItem['sku_exception_status'] = Orderui_Define_Const::BUSINESS_ORDER_SKU_NORMAL;
            if (isset($arrSkuItem['exception_info']) && !empty($arrSkuItem['exception_info'])) {
                $arrSkuParamsItem['sku_exception_status'] = Orderui_Define_Const::BUSINESS_ORDER_SKU_EXCEPTION;
            }
            $arrSkuParams[] = $arrSkuParamsItem;
        }
        return $arrSkuParams;
    }

    /**
     * 在sku列表中加入sku_name
     * @param $arrSkus
     * @return mixed
     * @throws Nscm_Exception_Error
     */
    protected function appendSkuNameToSkus($arrSkus) {
        if (empty($arrSkus)) {
            return $arrSkus;
        }
        $arrSkuIds = [];
        foreach ($arrSkus as $arrSkuItem) {
            $arrSkuIds[] = $arrSkuItem['sku_id'];
        }
        if (empty($arrSkuIds)) {
            return $arrSkus;
        }
        $arrMapSkus = $this->objDaoRalSku->getSkuInfos($arrSkuIds);
        foreach ((array)$arrSkus as $intKey => $arrSkuItem) {
            $intSkuId = $arrSkuItem['sku_id'];
            $arrSkus[$intKey]['sku_name'] = $arrMapSkus[$intSkuId]['sku_name'];
        }
        return $arrSkus;
    }

    /**
     * 拆分业态订单
     * @param  array $arrBusinessOrderInfo
     * @return array $arrOrderSysDetailList
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     * @throws Exception
     */
    public function splitBusinessOrder($arrBusinessOrderInfo)
    {
        $intBusinessOrderId = $arrBusinessOrderInfo['business_form_order_id'];
        if (empty($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::PARAM_ERROR, 'business_order_id invalid');
        }
        //check business order has been split
        if (!$this->checkBusinessOrderWhetherSplit($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT, 'business_order_id is already split');
        }
        $arrSkuIds = array_column($arrBusinessOrderInfo['skus'], 'sku_id');
        $arrSkuInfos = $this->objDaoRalSku->getSkuInfos($arrSkuIds);
        $arrBusinessOrderInfo['skus'] = $this->filterSkusByInfos($arrBusinessOrderInfo['skus'],
                                                                    $arrSkuInfos, $arrBusinessOrderInfo['business_form_order_type']);
        //split skus by sku temp
        $arrMapTmpSkus = $this->splitSkusBySkuTemp($arrBusinessOrderInfo['skus'], $arrSkuInfos);
        //get warehouse info
        $arrWarehouseInfo = $this->getWarehouseInfoByDistrictId($arrBusinessOrderInfo['customer_region_id']);
        $arrOrderSysDetailList = [];
        foreach ((array)$arrMapTmpSkus as $intSkuTmpType => $arrTmpSkus) {
            $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();
            $arrBusinessOrderInfo['skus'] = $arrTmpSkus;
            $arrBusinessOrderInfo['warehouse_id'] = $arrWarehouseInfo['warehouse_id'];
            $arrBusinessOrderInfo['warehouse_name'] = $arrWarehouseInfo['warehouse_name'];
            $arrOrderSysDetailList = [
                [
                    'order_system_id' => $intOrderSystemId,
                    'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                    'business_form_order_id' => $intBusinessOrderId,
                    'request_info' => $arrBusinessOrderInfo,
                ],
            ];
        }
        return $arrOrderSysDetailList;
    }

    /**
     * 根据sku温控类型拆单
     * @param $arrSkus
     * @param $arrSkuInfos
     * @return array
     */
    protected function splitSkusBySkuTemp($arrSkus, $arrSkuInfos)
    {
        if (empty($arrSkus)) {
            return [];
        }
        $arrMapTmpSkus = [];
        foreach ((array)$arrSkus as $arrSkuItem) {
            $intSkuId = $arrSkuItem['sku_id'];
            $intSkuTmpType = $arrSkuInfos[$intSkuId]['sku_temperature_control_type'];
            $arrMapTmpSkus[$intSkuTmpType][] = $arrSkuItem;
        }
        return $arrMapTmpSkus;
    }

    /**
     * 过滤无效的sku
     * @param $arrSkus
     * @param $arrSkuInfos
     * @param $intBusinessFormType
     * @return mixed
     * @throws Orderui_BusinessError
     */
    protected function filterSkusByInfos($arrSkus, $arrSkuInfos, $intBusinessFormType) {
        if (empty($arrSkuInfos) || empty($arrSkus)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SKU_INFO_PARAMS_ERROR);
        }
        //校验sku是否有效
        foreach ((array)$arrSkus as $intKey => $arrSkuItem) {
            $intSkuId = $arrSkuItem['sku_id'];
            if (empty($intSkuId)) {
                continue;
            }
            $arrSkuInfoItem = $arrSkuInfos[$intSkuId];
            if ($arrSkuInfoItem['is_active'] != Orderui_Define_Const::IS_ACTIVE) {
                unset($arrSkus[$intKey]);
            }
            $arrSkuBusinessForm = json_decode($arrSkuInfoItem['sku_business_form'], true);
            if (in_array($intBusinessFormType, $arrSkuBusinessForm)) {
                unset($arrSkus[$intKey]);
            }
        }
        if (empty($arrSkus)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SKU_INFO_INVALID);
        }
        return $arrSkus;
    }

    /**
     * 根据sku温控类型和区域id分配仓库
     * @param $arrMapTmpSkus
     * @param $intDistrictId
     * @return array
     * @throws Nscm_Exception_Error
     */
    protected function distributeWarehouseForSkus($arrMapTmpSkus, $intDistrictId)
    {
        $arrMapWarehouseIdSkus = [];
        $arrWarehouseInfos = $this->objDaoRalWarehouse->getWarehouseListByDistrictId($intDistrictId);
        if (empty($arrWarehouseInfos)) {
            return $arrMapWarehouseIdSkus;
        }
        foreach ((array)$arrWarehouseInfos as $arrWarehouseInfoItem) {
            $intWarehouseId = $arrWarehouseInfoItem['warehouse_id'];
            $intTmpType = $arrWarehouseInfos['warehouse_type'];
            $arrMapWarehouseIdSkus[$intWarehouseId] = $arrMapTmpSkus[$intTmpType];
        }
        return $arrMapWarehouseIdSkus;
    }

    /**
     * 根据区域id选择仓库
     * @param $intDistrictId
     * @return array
     * @throws Nscm_Exception_Error
     */
    protected function getWarehouseInfoByDistrictId($intDistrictId)
    {
        $arrWarehouseInfos = $this->objDaoRalWarehouse->getWarehouseListByDistrictId($intDistrictId);
        if (empty($arrWarehouseInfos)) {
            return [];
        }
        return $arrWarehouseInfos[0];
    }

    /**
     * 验证业态订单是否已经被拆分
     * @param int $intBusinessOrderId
     * @return bool
     */
    public function checkBusinessOrderWhetherSplit($intBusinessOrderId)
    {
        //从DB获取
        $arrOrderInfoInDB = Model_Orm_OrderSystem::getOrderInfoByBusinessOrderId($intBusinessOrderId);
        if (!empty($arrOrderInfoInDB)) {
            return false;
        }
        return true;
    }

    /**
     * 按订单系统类型分发订单
     * @param  array $arrOrderList
     * @return array
     * @throws Nscm_Exception_Error
     */
    public function distributeOrder($arrOrderList)
    {
        $ret = [];
        $objNwmsOrder = new Service_Data_NWmsOrder();
        foreach ($arrOrderList as $arrOrderInfo) {
            $intOrderSysType = $arrOrderInfo['order_system_type'];
            if (Orderui_Define_Const::ORDER_SYS_NWMS == $intOrderSysType) {
                $ret[] = [
                    'result' => $objNwmsOrder->createNWmsOrder($arrOrderInfo['request_info']),
                    'order_system_id' => $arrOrderInfo['order_system_id'],
                    'order_system_type' => $arrOrderInfo['order_system_type'],
                    'business_form_order_id' => $arrOrderInfo['business_form_order_id'],
                    'order_type' => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
                ];
            }
        }
        return $ret;
    }

    /**
     * 通过上游订单号验证是否存在业态订单信息
     * @param  integer $intSourceOrderId
     * @return bool
     */
    public function checkBusinessFormOrderWhetherExisted($intSourceOrderId)
    {
        $arrBusinessOrderInfo = Model_Orm_BusinessFormOrder::getOrderInfoBySourceOrderId($intSourceOrderId);
        if (empty($arrBusinessOrderInfo)) {
            return false;
        }
        return true;
    }

    /**
     * 取消物流单
     * @param $intLogisticsOrderId
     * @param $strRemark
     * @return bool
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     */
    public function cancelLogisticsOrder($intLogisticsOrderId, $strRemark)
    {
        $arrBusinessOrderInfo = Model_Orm_BusinessFormOrder::getOrderInfoBySourceOrderId($intLogisticsOrderId);
        $intBusinessFormOrderId = $arrBusinessOrderInfo['business_form_order_id'];
        //预取消沧海出库单
        usleep(Orderui_Define_Const::CANCEL_DELAY_MICRO_SECONDS);
        $arrStockoutOrderInfo = Model_Orm_OrderSystemDetail::
                        getOrderInfoByBusinessFormOrderIdAndType($intBusinessFormOrderId,
                                                    Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);

        if (empty($arrStockoutOrderInfo)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NWMS_ORDER_NOT_FOUND);
        }
        $intStockoutOrderId = $arrStockoutOrderInfo[0]['order_id'];
        $arrRet = $this->objDaoRalNWmsOrder->preCancelStockoutOrder($intStockoutOrderId);
        if (isset($arrRet['error_no']) && 0 != $arrRet['error_no']) {
            $strErrorMsg = sprintf(Orderui_Define_BusinessFormOrder::OMS_CANCEL_FAILED_MESSAGE,
                                    $arrRet['error_msg']);
            Bd_Log::warning(sprintf("method[%s] order_id[%s] arrRet[%s] precancel failed",
                            __METHOD__, $intStockoutOrderId, json_encode($arrRet)));
            Orderui_BusinessError::throwException($arrRet['error_no'], $strErrorMsg);
        }
        //取消tms运单
        $arrShipmentOrderInfo = Model_Orm_OrderSystemDetail::getOrderInfoByBusinessFormOrderIdAndType($intBusinessFormOrderId,
                                                                Orderui_Define_Const::NWMS_ORDER_TYPE_SHIPMENT_ORDER);
        if (empty($arrShipmentOrderInfo)) {
            $this->objDaoRalNWmsOrder->rollbackCancelStockoutOrder($intStockoutOrderId);
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_TMS_ORDER_NOT_FOUND);
        }
        $intShipmentOrderId = $arrShipmentOrderInfo[0]['order_id'];
        $arrRet = $this->objDaoWrpcTms->cancelShipmentOrder($intShipmentOrderId, $strRemark);
        if (isset($arrRet['errno']) && 0 != $arrRet['errno']) {
            $this->objDaoRalNWmsOrder->rollbackCancelStockoutOrder($intStockoutOrderId);
            Bd_Log::warning(sprintf("method[%s] order_id[%s] arrRet[%s] cancel shipment failed",
                            __METHOD__, $intShipmentOrderId, json_encode($arrRet)));
            Orderui_BusinessError::throwException($arrRet['errno'],
                                    Orderui_Define_BusinessFormOrder::OMS_CANCEL_SHIPMENT_ORDER_FAILED);
        }
        //确认取消wms出库单
        $arrRet = $this->objDaoRalNWmsOrder->confirmCancelStockoutOrder($intStockoutOrderId, $strRemark);
        if (isset($arrRet['errno']) && 0 != $arrRet['errno']) {
            $strErrorMsg = sprintf(Orderui_Define_BusinessFormOrder::OMS_CANCEL_FAILED_MESSAGE,
                $arrRet['error_msg']);
            Bd_Log::warning(sprintf("method[%s] order_id[%s] arrRet[%s] cancel failed",
                __METHOD__, $intStockoutOrderId, json_encode($arrRet)));
            Orderui_BusinessError::throwException($arrRet['error_no'], $strErrorMsg);
        }
        return Orderui_Define_Const::CANCEL_SUCCESS;
    }

    /**
     * 获取逆向订单创建标识
     * @param $intSourceOrderId
     * @return mixed
     */
    public function getReverseSourceOrderFlag($intSourceOrderId)
    {
        return $this->objDaoRedisBsOrder->getReverseSourceOrder($intSourceOrderId);
    }

    /**
     * 设置逆向订单创建标识
     * @param integer $intSourceOrderId
     * @return void
     */
    public function setReverseSourceOrderFlag($intSourceOrderId)
    {
        $this->objDaoRedisBsOrder->setReverseSourceOrderKey($intSourceOrderId);
    }
}