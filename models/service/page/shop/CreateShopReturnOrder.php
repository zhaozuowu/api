<?php
/**
 * @name Service_Page_Shop_CreateShopReturnOrder
 * @desc 门店创建退货单
 * @author huabang.xue@ele.me
 * Date: 2018/3/26
 * Time: 下午4:23
 */
class Service_Page_Shop_CreateShopReturnOrder
{
    /*
     * @var objData
     */
    protected $objData;
    /*
     * init object
     */
    public function __construct()
    {
        $this->objData = new Service_Data_BusinessFormOrder();
    }

    /*
     * @desc 门店签收
     * @param arr $arrInput
     * @return true
     */
    public function execute($arrInput)
    {
        //悲观锁校验
        $intOrderFlag = $this->objData->getShopReturnOrderFlag($arrInput['logistics_order_id']);
        if ($intOrderFlag) {
            return true;
        }
        $strCmd = Orderui_Define_Cmd::CMD_CREATE_SHOP_RETURN_ORDER;
        $wmqRet = Orderui_Wmq_Commit::sendWmqCmd($strCmd, $arrInput, $arrInput['logistics_order_id']);
        if (false == $wmqRet) {
            Bd_Log::warning(sprintf("method[%s] cmd[%s] error", __METHOD__, $strCmd));
        }
        //设置悲观锁
        $this->objData->setShopReturnOrderFlag($arrInput['logistics_order_id']);
        return true;
    }
}