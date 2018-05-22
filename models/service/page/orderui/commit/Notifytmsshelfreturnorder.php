<?php
/**
 * @name Service_Page_Orderui_Commit_Notifytmsshelfreturnorder
 * @desc 通知TMS撤点订单盘点数据
 * @author huabang.xue@ele.me
 */
class Service_Page_Orderui_Commit_Notifytmsshelfreturnorder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_ShipmentOrder
     */
    protected $objDataShipmentOrder;

    public function __construct()
    {
        $this->objDataShipmentOrder = new Service_Data_ShipmentOrder();
    }

    /**
     * @param $arrInput
     * @return mixed
     * @throws Exception
     * @throws Nscm_Exception_Error
     * @throws Orderui_BusinessError
     * @throws Wm_Error
     */
    public function myExecute($arrInput)
    {
        Bd_Log::trace(sprintf('method[%s] request %s', __METHOD__, json_encode($arrInput)));
        //创建逆向业态单
        $res = $this->objDataShipmentOrder->notifyReserveOrderCheckData(
                $arrInput['shipment_order_id'],
                $arrInput['warehouse_id'],
                $arrInput['supply_type'],
                $arrInput['shelf_infos'],
                $arrInput['skus']
            );
        Bd_Log::trace(sprintf('method[%s] ret %s', __METHOD__, $res));
        return $res;
    }
}
