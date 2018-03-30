<?php

/**
 * @property int $id
 * @property int $order_system_detail_id
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
     * @param  integer $intOrderSysId
     * @return array
     */
    public static function getOrderInfo($intOrderId, $intOrderType, $intOrderSysId)
    {
        $arrCondition = [
            'order_type' => $intOrderType,
            'order_id' => $intOrderId,
            'order_system_id' => $intOrderSysId,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }

    /**
     * 通过下游系统订单号和订单类型获取订单信息
     * @param  integer $intOrderId
     * @param  integer $intOrderType
     * @return array
     */
    public static function getOrderInfoByOrderIdAndType($intOrderId, $intOrderType)
    {
        $arrCondition = [
            'order_id' => $intOrderId,
            'order_type' => $intOrderType,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }

    /**
     * 通过业态订单和类型获取下游订单信息
     * @param $intBusinessFormOrderId
     * @param $intOrderType
     * @return mixed
     */
    public static function getOrderInfoByBusinessFormOrderIdAndType($intBusinessFormOrderId, $intOrderType) {
        $arrCondition = [
            'business_form_order_id' => $intBusinessFormOrderId,
            'order_type' => $intOrderType,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRows(self::getAllColumns(), $arrCondition);
    }
}
