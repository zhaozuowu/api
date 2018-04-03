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
     * init
     */
    public function __construct()
    {
        $this->objWrpcService = new Bd_Wrpc_Client(Orderui_Define_Wrpc::TMS_APP_ID,
            Orderui_Define_Wrpc::TMS_NAMESPACE,
            Orderui_Define_Wrpc::TMS_SERVICE_NAME);
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
        $arrSignupRequest['skus'] = $this->getSkus($arrSkus);
        $arrParams['shipmentId'] = $intShipmentOrderId;
        $arrParams['request'] = $arrSignupRequest;
        $arrParams['user'] = (object)$arrUser;
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
}