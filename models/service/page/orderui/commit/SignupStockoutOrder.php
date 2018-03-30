<?php
/**
 * @name Service_Page_Orderui_Commit_Signupstockoutorder
 * @desc 异步签收出库单
 * @author huabang.xue@ele.me
 */
class Service_Page_Orderui_Commit_Signupstockoutorder extends Wm_Lib_Wmq_CommitPageService {
    
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