<?php
/**
 * @name ShopObverse.php
 * @desc ShopObverse.php
 * @author yu.jin03@ele.me
 */

class Service_Data_Router_ShopObverse extends Orderui_Base_OrderRouter
{
    /**
     * @var Dao_Redis_BusinessOrder
     */
    protected $objDaoRedisBsOrder;

    /**
     * @var Dao_Ral_Sku
     */
    protected $objDaoRalSku;

    /**
     * @var Dao_Ral_Warehouse
     */
    protected $objDaoRalWarehouse;

    /**
     * @var Dao_Wrpc_Nwms
     */
    protected $objDaoWrpcNwms;

    /**
     * Service_Data_Router_ShopObverse constructor.
     */
    public function __construct()
    {
        $this->objDaoRedisBsOrder = new Dao_Redis_BusinessOrder();
        $this->objDaoRalSku = new Dao_Ral_Sku();
        $this->objDaoRalWarehouse = new Dao_Ral_Warehouse();
        $this->objDaoWrpcNwms = new Dao_Wrpc_Nwms();
    }

    /**
     * 门店正向拆分
     * @param $arrBusinessOrderInfo
     * @param $intBusinessOrderId
     * @return array
     * @throws Nscm_Exception_Error
     * @throws Wm_Error
     */
    protected function splitOrder($arrBusinessOrderInfo, $intBusinessOrderId)
    {
        $arrSkuIds = array_column($arrBusinessOrderInfo['skus'], 'sku_id');
        $arrSkuInfos = $this->objDaoRalSku->getSkuInfos($arrSkuIds);
        //split skus by sku temp
        $arrMapTmpSkus = $this->splitSkusBySkuTemp($arrBusinessOrderInfo['skus'], $arrSkuInfos);
        //get warehouse info
        $arrWarehouseInfo = $this->getWarehouseInfoByDistrictId($arrBusinessOrderInfo['customer_region_id']);
        $arrOrderSysDetailList = [];
        $intOrderSystemId = Orderui_Util_Utility::generateOmsOrderCode();
        foreach ((array)$arrMapTmpSkus as $intSkuTmpType => $arrTmpSkus) {
            $intOrderSystemDetailId = Orderui_Util_Utility::generateOmsOrderCode();
            $arrBusinessOrderInfo['skus'] = $arrTmpSkus;
            $arrBusinessOrderInfo['warehouse_id'] = $arrWarehouseInfo['warehouse_id'];
            $arrBusinessOrderInfo['warehouse_name'] = $arrWarehouseInfo['warehouse_name'];
            $arrOrderSysDetail = [
                'order_system_id' => $intOrderSystemId,
                'order_system_detail_id' => $intOrderSystemDetailId,
                'order_system_type' => Orderui_Define_Const::ORDER_SYS_NWMS,
                'business_form_order_id' => $intBusinessOrderId,
                'request_info' => $arrBusinessOrderInfo,
            ];
            $arrOrderSysDetailList[] = $arrOrderSysDetail;
        }
        return $arrOrderSysDetailList;
    }

    /**
     * 门店正向转发
     * @param $arrOrderList
     * @param $intSourceOrderId
     * @return array
     */
    protected function distributeOrder($arrOrderList, $intSourceOrderId)
    {
        $arrRet = [];
        //检查批量创建参数缓存
        $arrCacheOrderList = $this->objDaoRedisBsOrder->getBatchCreateOrderParams($intSourceOrderId);
        if (!empty($arrCacheOrderList)) {
            $arrOrderList = $arrCacheOrderList;
        }
        //调用沧海批量创建订单
        $arrNwmsOrders = $this->objDaoWrpcNwms->batchCreateBusinessOrder($arrOrderList);
        $arrMapNwmsOrders = [];
        foreach ((array)$arrNwmsOrders as $arrNwmsOrderItem) {
            $intOrderSysId = $arrNwmsOrderItem['result']['logistics_order_id'];
            $arrMapNwmsOrders[$intOrderSysId] = $arrNwmsOrderItem;
        }
        //格式化返回结果
        foreach ((array)$arrOrderList as $arrOrderInfo) {
            $intOrderSysId = $arrOrderInfo['order_system_detail_id'];
            $arrRet[] = [
                'result' => $arrMapNwmsOrders[$intOrderSysId],
                'order_system_id' => $intOrderSysId,
                'business_form_order_id' => $arrOrderInfo['business_form_order_id'],
                'order_type' => Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_ORDER,
                'order_system_type' => $arrOrderInfo['order_system_type'],
                'logistics_order_id' => $arrOrderInfo['request_info']['logistics_order_id'],
                'warehouse_id' => $arrOrderInfo['request_info']['warehouse_id'],
            ];
        }
        //缓存参数
        $this->objDaoRedisBsOrder->setBatchCreateOrderParams($intSourceOrderId, $arrOrderList);
        return $arrRet;
    }

    /**
     * 门店正向按温控类型拆分
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
        //mock数据
        foreach ((array)$arrSkus as $arrSkuItem) {
            $intSkuId = $arrSkuItem['sku_id'];
            $intSkuTmpType = $arrSkuInfos[$intSkuId]['sku_temperature_control_type'];
            $arrMapTmpSkus[$intSkuTmpType][] = $arrSkuItem;
        }
        return $arrMapTmpSkus;
    }

    /**
     * 根据区域id获取仓库信息
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
}