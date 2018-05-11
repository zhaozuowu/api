<?php
/**
 * @name Service_Page_Orderui_Commit_Createshelfreturnorder
 * @desc 创建门店撤点销退入库单
 * @author huabang.xue@ele.me
 */
class Service_Page_Orderui_Commit_Createshelfreturnorder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDataBusinessFormOrder;

    public function __construct()
    {
        $this->objDataBusinessFormOrder = new Service_Data_BusinessFormOrder();
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
        $res = $this->objDataBusinessFormOrder->createShelfReturnOrder(
                $arrInput['logistics_order_id'],
                $arrInput['shelf_infos'],
                $arrInput['skus'],
                $arrInput['remark']
            );
        Bd_Log::trace(sprintf('method[%s] ret %s', __METHOD__, $res));
        return $res;
    }
}
