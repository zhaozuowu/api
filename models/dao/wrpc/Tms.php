<?php
/**
 * @name Dao_Wrpc_Tms
 * @desc interact with tms
 * @author jinyu02@iwaimai.baidu.com
 */
class Dao_Wrpc_Tms
{
    /**
     * wrcp service
     * @var Bd_Wrpc_Client
     */
    private $objWrpcService;

    /**
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::TMS_APP_ID,
                                                    Orderui_Define_Wrpc::TMS_NAMESPACE,
                                                    Orderui_Define_Wrpc::TMS_SERVICE_NAME);
    }

    /**
     * 调用tms签收
     * @param $intShipmentOrderId
     * @param $intBizType
     * @param $arrSkus
     * @param $arrUser
     * @return mixed
     */
    public function signupShipmentOrder($intShipmentOrderId, $intBizType, $arrSkus, $arrUser)
    {
        $arrParams = $this->getSignupParams($intShipmentOrderId, $intBizType, $arrSkus, $arrUser);
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
    public function getSignupParams($intShipmentOrderId, $intBizType, $arrSkus, $arrUser)
    {
        $arrParams = [];
        $arrSignupRequest = [];
        $arrSignupRequest['shipmentId'] = $intShipmentOrderId;
        $arrSignupRequest['bizType'] = $intBizType;
        $arrSignupRequest['skus'] = $arrSkus;
        $arrParams['shipmentId'] = $intShipmentOrderId;
        $arrParams['request'] = $arrSignupRequest;
        $arrParams['user'] = $arrUser;
        return $arrParams;
    }
}