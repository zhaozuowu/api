<?php
/**
 * @name Service_Page_UpdateOmsOrderInfo
 * @desc 增量更新Oms子订单信息
 * @author hang.song02@ele.me
 */

class Service_Page_UpdateOmsOrderInfo
{
    /**
     * @var Service_Data_OmsDetailOrder
     */
    protected $objData;
    /**
     * Service_Page_UpdateOmsOrderInfo constructor.
     */
    public function __construct()
    {
        $this->objData = new Service_Data_OmsDetailOrder();
    }

    /**
     * @param array $arrInput
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     * @throws Exception
     */
    public function execute($arrInput)
    {
        $arrOrderDetailList = $this->objData->assembleOmsSysDetailInfo($arrInput['order_info']);
        $arrOrderSkuList = $this->objData->assembleOmsSysDetailSkuInfo($arrOrderDetailList);
        $this->objData->addOrderSysDetail($arrOrderDetailList, $arrOrderSkuList);
    }
}