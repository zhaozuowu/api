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
     * init object
     */
    public function __construct()
    {
        $this->objDaoRalSku = new Dao_Ral_Sku();
    }

    /**
     * 授权介入校验
     * @param $strBusinessFormKey
     * @param $strBusinessFormToken
     * @return void
     * @throws Orderui_Error
     */
    public function checkAuthority($strBusinessFormKey, $strBusinessFormToken) {
        $strGenKey = md5(md5($strBusinessFormKey) . md5(Orderui_Define_BusinessFormOrder::SALT_VAL));
        if ($strGenKey != $strBusinessFormToken) {
            Orderui_Error::throwException(Orderui_Error_Code::OMS_CHECK_AUTHORITY_ERROR);
        }
    }

    /**
     * 创建业态订单
     * @param $arrInput
     * @return void
     * @throws Nscm_Exception_Error
     * @throws Orderui_Error
     * @throws Wm_Error
     */
    public function createBusinessFormOrder($arrInput) {
        //$this->checkAuthority($arrInput['business_form_key'], $arrInput['business_form_token']);
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
        $arrSkuInfoMap = [];
        foreach ($arrBusinessOrderInfo['skus'] as $arrSkuInfo) {
            $arrSkuInfoMap[$arrSkuInfo['sku_id']] = $arrSkuInfo['order_amount'];
        }
        //check business order has been split
        if (!$this->checkBusinessOrderWhetherSplit($intBusinessOrderId)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::BUSINESS_ORDER_IS_SPLIT, 'business_order_id is already split');
        }

        $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();

        $arrOrderSysList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
            ],
        ];

        $arrOrderSysDetailList = [
            [
                'order_system_id' => $intOrderSystemId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
                'request_info' => $arrBusinessOrderInfo,
            ],
        ];
        //转发
        $res = $this->distributeOrder($arrOrderSysDetailList);
        if (empty($res)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_ORDER_CREATE_ERROR);
        }
        $arrOrderSysDetailListDb = [];
        $arrOrderSysDetailSkuListDb = [];
        foreach ($res as $re) {
            $strOrderException = '';
            $strOrderExceptionTime = '';
            $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
            foreach ($re['result']['exceptions'] as $arrSkuException) {
                if ($arrSkuException['sku_id'] == 0) {
                    $strOrderException = $arrSkuException['exception_info'];
                    $strOrderExceptionTime = $arrSkuException['exception_time'];
                } else {
                    $arrOrderSysDetailSkuListDb[] = [
                        'order_system_detail_order_id' => $intOrderSystemDetailId,
                        'order_id' => $re['result']['result']['business_form_order_id'],
                        'sku_id' => $arrSkuException['sku_id'] ,
                        'sku_amount' => $arrSkuInfoMap[$arrSkuException['sku_id']],
                        'sku_exception' => $arrSkuException['exception_info'],
                    ];
                }
            }
            foreach ($re['result']['result']['skus'] as $arrSku) {
                $arrSkuItem = [
                    'order_system_detail_order_id' => $intOrderSystemDetailId,
                    'order_id' => $re['result']['result']['business_form_order_id'],
                    'sku_id' => $arrSku['sku_id'],
                    'sku_amount' => $arrSkuInfoMap[$arrSku['sku_id']],
                    'sku_exception' => '',
                ];
                if (!empty($strOrderException)) {
                    $arrSkuItem['sku_exception'] = $strOrderException;
                }
                $arrOrderSysDetailSkuListDb[] = $arrSkuItem;
            }

            $arrOrderSysDetailListDb[] = [
                'order_system_detail_order_id' => $intOrderSystemDetailId,
                'order_system_id' => $re['order_system_id'],
                'order_type' => $re['order_type'],
                'business_form_order_id' => $re['business_form_order_id'],
                'parent_order_id' => $re['order_system_id'],
                'order_id' => $re['result']['result']['business_form_order_id'],
                'order_exception' => $strOrderException,
            ];
        }
        Model_Orm_OrderSystem::getConnection()->transaction(function () use ($arrOrderSysList, $arrOrderSysDetailListDb, $arrOrderSysDetailSkuListDb) {
            Model_Orm_OrderSystem::batchInsert($arrOrderSysList);
            Model_Orm_OrderSystemDetail::batchInsert($arrOrderSysDetailListDb);
            Model_Orm_OrderSystemDetailSku::batchInsert($arrOrderSysDetailSkuListDb);
        });
        return $res;
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
                $ret[] = $objNwmsOrder->createNWmsOrder($arrOrderInfo['request_info']);
            }
        }
        return $ret;
    }
}