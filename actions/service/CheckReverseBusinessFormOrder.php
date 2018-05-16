<?php
/**
 * @name Action_Service_CheckReverseBusinessFormOrder
 * @desc Action_Service_CheckReverseBusinessFormOrder
 * @author hang.song02@ele.me
 */

class Action_Service_CheckReverseBusinessFormOrder extends Orderui_Base_ServiceAction
{
    /**
     * arrInput
     * @var array
     */
    protected $arrInputParams = [
        'logistics_order_id' => 'int|required',
        'shelf_infos' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'device_no' => 'str|required',
                'device_type' => 'int|required|min[0]',
            ],
        ],
        'skus' => [
            'validate' => 'arr|required',
            'type' => 'array',
            'params' => [
                'sku_id' => 'int|required',
                'return_amount' => 'int|required',
            ],
        ],
    ];

    /**
     * method
     * @var int
     */
    protected $intMethod = Orderui_Define_Const::METHOD_POST;

    /**
     * init
     * @return mixed|void
     */
    public function myConstruct()
    {
        $this->objPage = new Service_Page_CheckReverseBusinessFormOrder();
    }

    /**
     * format
     * @param $arrRet
     * @return mixed
     */
    public function format($arrRet)
    {
        return $arrRet;
    }
}