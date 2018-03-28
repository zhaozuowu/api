<?php
/**
 * Created by PhpStorm.
 * User: xhb
 * Date: 2018/3/26
 * Time: 下午4:31
 */
class Service_Data_ShipmentOrder
{
    /*
     * @var object
     */
    protected $objDaoRalNwmsOrder;
    /*
     * @var object
     */
    protected $objDaoWprcTms;
    /*
     * init object
     */
    public function __construct()
    {
        $this->objDaoRalNwmsOrder = new Dao_Ral_NWmsOrder();
        $this->objDaoWprcTms = new Dao_Wrpc_Tms();
    }

    /*
     * 接收签收运单请求并转发wms和tms
     */
    public function SignupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, $strSinupSkus, $strOffShelfSkus)
    {
        $arrRet = [
            'shipment_order_id' => strval($intShipmentOrderId),
            'result' => false,
        ];
        $arrShipmentOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intShipmentOrderId,
            Nscm_Define_OmsOrder::TMS_ORDER_TYPE_SHIPMENT);
        if (empty($arrShipmentOrder)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_SHIPMENT_ORDER);
        }

        $intStockOutOrderId = $arrShipmentOrder['parent_order_id'];
        $arrStockoutOrder = Model_Orm_OrderSystemDetail::getOrderInfoByOrderIdAndType($intStockOutOrderId, Nscm_Define_OmsOrder::NWMS_ORDER_TYPE_STOCK_OUT);
        if (empty($arrStockoutOrder)) {
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_NOT_FOUND_STOCKOUT_ORDER);
        }
        //转发nwms
        $arrParam = [
            'stockout_order_id' => $intStockOutOrderId,
            'signup_satus'      => $intSignupStatus,
            'signup_skus'       => $strSinupSkus,
        ];
        $strCmd = Orderui_Define_Cmd::CMD_SIGNUP_STOCKOUT_ORDER;
        $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, strval($intStockOutOrderId));
        if (false == $ret) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SIGNUP_STOCKOUT_ORDER_FAIL);
        }
        //转发tms
        $arrParamTms = [
            'shipment_order_id' => $intShipmentOrderId,
            'signup_satus'      => $intSignupStatus,
            'signup_skus'       => $strSinupSkus,
            'offshelf_skus'     => $strOffShelfSkus,
        ];
        $strCmdTms = Orderui_Define_Cmd::CMD_TRANSMIT_SIGNUP_DATA;
        $retTms = Orderui_Wmq_Commit::sendWmqCmd($strCmdTms, $arrParamTms, strval($intShipmentOrderId));
        if (false == $retTms) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmdTms));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SIGNUP_SHIPMENT_ORDER_FAIL);
        }
        $arrRet['result'] = true;
        return $arrRet;
    }
    /*
     * 签收wms出库单
     */
    public function SignupStockoutOrder($arrSignupData)
    {
        return $this->objDaoRalNwmsOrder->signupStockoutOrder($arrSignupData);
    }
    /*
     * 签收TMS运单
     */
    public function SignupShipmentOrder($arrSignupData)
    {
        return $this->objDaoWprcTms->SignupShipmentOrder($arrSignupData);
    }
}