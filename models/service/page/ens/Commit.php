<?php
/**
 * @name Service_Page_Ens_Commit
 * @desc Service_Page_Ens_Commit
 * @author bochao.lv@ele.me
 */

class Service_Page_Ens_Commit extends Wm_Lib_Wmq_CommitPageService
{
    /**
     * do execute
     * @param array $arrRequest
     * @throws Orderui_BusinessError
     */
    public function myExecute($arrRequest)
    {
        // get branch number
        // this step should be put on action layer
        // but wmq base action has a bad expandability
        if (preg_match('/^\/orderui\/ens\/recv(\d)$/', $_SERVER['REQUEST_URI'], $matches)) {
            $intBranch = $matches[1];
            $dataDeliver = new Service_Data_Ens_Deliver();
            $strEvent = strval($arrRequest['event_key']);
            $arrData = $arrRequest['data'];
            $dataDeliver->deliver($strEvent, $arrData, $intBranch);
        } else {
            Bd_Log::warning('url error!');
            return;
        }
    }
}