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
     * 创建业态订单
     * @param $arrInput
     * @return void
     */
    public function createBusinessFormOrder($arrInput) {
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
     */
    protected function getCreateParams($arrInput) {
        $arrCreateParams = [];
        if (empty($arrInput)) {
            return $arrCreateParams;
        }
        $arrCreateParams['business_form_order_id'] = Orderui_Util_Utility::generateBusinessFormOrderId();
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
}