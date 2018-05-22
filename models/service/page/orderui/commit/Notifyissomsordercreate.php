<?php
/**
 * @name Notifyissomsordercreate.php
 * @desc Notifyissomsordercreate.php
 * @author yu.jin03@ele.me
 */

class Service_Page_Orderui_Commit_Notifyissomsordercreate extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * @var Service_Data_BusinessFormOrder
     */
    protected $objDsBusinessFormOrder;

    /**
     * Service_Page_Orderui_Commit_Notifyissomsordercreate constructor.
     */
    public function __construct()
    {
        $this->objDsBusinessFormOrder = new Service_Data_BusinessFormOrder();
    }

    public function myExecute($arrRequest)
    {
        $this->objDsBusinessFormOrder->notifyIssOrderCreate($arrRequest);
    }
}