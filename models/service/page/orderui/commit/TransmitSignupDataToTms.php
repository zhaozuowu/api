<?php
/**
 * @name Service_Page_Orderui_Commit_TransmitSignupDataToTms
 * @desc 异步创建出库单
 * @author jinyu02@iwaimai.baidu.com
 */
class Service_Page_Orderui_Commit_TransmitSignupDataToTms extends Wm_Lib_Wmq_CommitPageService {

    /**
     * @var Service_Data_ShipmentOrder
     */
    protected $objDataShipmentOrder;

    /**
     * init
     */
    public function __construct() {
        $this->objDataShipmentOrder = new Service_Data_ShipmentOrder();
    }

    /**
     * create stockout order
     * @param array $arrInput
     * @return bool
     * @throws Order_BusinessError
     */
    public function myExecute($arrInput) {
        Bd_Log::trace(sprintf("method[%s] arrInput[%s]", __METHOD__, json_encode($arrInput)));
        $boolRet = $this->objDataShipmentOrder->SignupStockoutOrder($arrInput);
        return $boolRet;
    }
}