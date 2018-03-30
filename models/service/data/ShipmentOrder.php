<?php
/**
 * @name Service_Data_ShipmentOrder
 * @desc 运单相关逻辑
 * @author huabang.xue@ele.me
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

    public function signupShipmentOrder($intShipmentOrderId, $arrSignupSkus, $intBizType) {
        $this->objDaoWprcTms->signupShipmentOrder($intShipmentOrderId,$arrSignupSkus, $intBizType);
    }

    /*
     * 接收签收运单请求并转发wms和tms
     */
    public function SignupShipmentOrderByInput($intShipmentOrderId, $intSignupStatus, $arrSinupSkus, $arrOffShelfSkus, $arrAdjustSkus)
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
        //存储库存调整sku
        if (!empty($arrAdjustSkus)) {
            $intBusinessFormOrderId = intval($arrShipmentOrder['business_form_order_id']);
            $objBusinessFormOrder = Model_Orm_BusinessFormOrder::getBusinessFormOrderByBusinessOrderId($intBusinessFormOrderId);
            if (!empty($objBusinessFormOrder)) {
                $arrBusinessFormOrderExt = json_decode($objBusinessFormOrder['business_form_ext'], true);
                $arrBusinessFormOrderExt['adjust_stock_skus'] = $arrAdjustSkus;
                $arrRow['business_form_ext'] = json_encode($arrBusinessFormOrderExt);
                $objBusinessFormOrder->update($arrRow);
            }
        }
        //转发nwms
        $arrParam = [
            'stockout_order_id' => $intStockOutOrderId,
            'signup_satus'      => $intSignupStatus,
            'signup_skus'       => $arrSinupSkus,
        ];
        $strCmd = Orderui_Define_Cmd::CMD_SIGNUP_STOCKOUT_ORDER;
        $ret = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrParam, strval($intStockOutOrderId));
        if (false == $ret) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SIGNUP_STOCKOUT_ORDER_FAIL);
        }
        //转发tms
        /*暂时不传tms
        $arrParamTms = [
            'shipment_order_id' => $intShipmentOrderId,
            'signup_satus'      => $intSignupStatus,
            'signup_skus'       => $arrSinupSkus,
            'offshelf_skus'     => $arrOffShelfSkus,
        ];
        $strCmdTms = Orderui_Define_Cmd::CMD_TRANSMIT_SIGNUP_DATA;
        $retTms = Orderui_Wmq_Commit::sendWmqCmd($strCmdTms, $arrParamTms, strval($intShipmentOrderId));
        if (false == $retTms) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmdTms));
            Orderui_BusinessError::throwException(Orderui_Error_Code::OMS_SIGNUP_SHIPMENT_ORDER_FAIL);
        }
        */
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
}