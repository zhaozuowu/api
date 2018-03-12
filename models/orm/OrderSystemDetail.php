<?php

/**
 * @property int $id
 * @property int $order_system_detail_order_id
 * @property int $order_system_id
 * @property int $order_type
 * @property int $business_form_order_id
 * @property int $parent_order_id
 * @property int $children_order_id
 * @property int $order_id
 * @property string $order_exception
 * @property int $create_time
 * @property int $update_time
 * @property int $is_delete
 * @property int $version
 * @method static Model_Orm_OrderSystemDetail findOne($condition, $orderBy = [], $lockOption = '')
 * @method static Model_Orm_OrderSystemDetail[] findAll($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static Generator|Model_Orm_OrderSystemDetail[] yieldAll($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static Model_Orm_OrderSystemDetail findOneFromRdview($condition, $orderBy = [], $lockOption = '')
 * @method static findRowFromRdview($columns, $condition, $orderBy = [])
 * @method static Model_Orm_OrderSystemDetail[] findAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static findRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findValueFromRdview($column, $cond, $orderBy = [])
 * @method static findFromRdview($cond = [])
 * @method static findBySqlFromRdview($sql, $asArray = true)
 * @method static countFromRdview($cond, $column = '*')
 * @method static existsFromRdview($cond)
 * @method static Generator|Model_Orm_OrderSystemDetail[] yieldAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 */

class Model_Orm_OrderSystemDetail extends Orderui_Base_Orm
{

    public static $tableName = 'order_system_detail';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';

    /**
     * 通过oms订单号获取Oms订单信息
     * @param  integer $intOrderType
     * @param  integer $intOrderId
     * @return array
     */
    public static function getOrderInfoByOrderId($intOrderId, $intOrderType)
    {
        $arrCondition = [
            'order_type' => $intOrderType,
            'order_id' => $intOrderId,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }

    /**
     * 创建order system detail 记录
     * @param  integer $intOrderSysId
     * @param  integer $intOrderType
     * @param  integer $intBusinessFormOrderId
     * @param  integer $intOrderId
     * @param  integer $intParentOrderId
     * @param  integer $intChildrenOrderId
     * @param  string  $strOrderException
     * @return integer $intOrderSysDetailOrderId
     * @throws Wm_Error
     */
    public static function insertOrderSysDetail($intOrderSysId, $intOrderType,
                $intBusinessFormOrderId, $intOrderId, $intParentOrderId, $intChildrenOrderId, $strOrderException)
    {
        $intOrderSysDetailOrderId = Orderui_Util_Util::generateOmsOrderCode();
        $arrOrderSysDetailData = [
            'order_system_detail_order_id' => $intOrderSysDetailOrderId,
            'order_system_id' => $intOrderSysId,
            'order_type' => $intOrderType,
            'business_form_order_id' => $intBusinessFormOrderId,
            'parent_order_id' => $intParentOrderId,
            'order_id' => $intOrderId,
            'children_order_id' => $intChildrenOrderId,
            'order_exception' => $strOrderException,
        ];
        Model_Orm_OrderSystemDetail::insert($arrOrderSysDetailData);
        return $intOrderSysDetailOrderId;
    }
}
