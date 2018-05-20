<?php
/**
 * @name Createreverseshelforder.php
 * @desc Createreverseshelforder.php
 * @author yu.jin03@ele.me
 */
class Service_Page_Orderui_Commit_Createreverseshelforder extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
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
        Bd_Log::trace(sprintf("method[%s] params[%s]", __METHOD__, json_encode($arrInput)));
        //创建逆向业态单
        $this->objDsBusinessFormOrder->createOrder($arrInput);
    }
}
