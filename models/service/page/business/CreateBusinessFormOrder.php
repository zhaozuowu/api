<?php
/**
 * @name Service_Page_Business_CreateBusinessFormOrder
 * @desc Service_Page_Business_CreateBusinessFormOrder
 * @author yu.jin03@ele.me
 */
class Service_Page_Business_CreateBusinessFormOrder
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * init object
     */
    public function __construct() {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    /**
     * 业态订单创建并拆分转发
     * @param  array $arrInput
     * @return array
     * @throws Nscm_Exception_Error
     * @throws Wm_Error
     * @throws Exception
     */
    public function execute($arrInput) {
        $arrInput['business_form_order_way'] = Orderui_Define_BusinessFormOrder::ORDER_WAY_OBVERSE;
        $arrInput['order_supply_type'] = intval($arrInput['shelf_info']['supply_type']);
        if (!in_array($arrInput['order_supply_type'],
            [Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_CREATE
                , Orderui_Define_BusinessFormOrder::ORDER_SUPPLY_TYPE_SUPPLY])) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::NWMS_BUSINESS_FORM_ORDER_SUPPLY_TYPE_ERROR);
        }
        $this->objDsBusinessFormOrder->checkCreateParams($arrInput);
        if (Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_SHELF
            == $arrInput['business_form_order_type']) {
            $arrRet = $this->objDsBusinessFormOrder->createOrder($arrInput);
            return $arrRet[0]['result']['result'];
        }
        Orderui_Wmq_Commit::sendWmqCmd(Orderui_Define_Cmd::CMD_CREATE_OMS_ORDER,
            $arrInput, $arrInput['logistics_order_id']);
        return [];
    }
}