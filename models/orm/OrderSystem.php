<?php

/**
 * @property int $id
 * @property int $order_system_id
 * @property int $order_system_type
 * @property int $business_form_order_id
 * @property int $create_time
 * @property int $update_time
 * @property int $is_delete
 * @property int $version
 * @method static Model_Orm_OrderSystem findOne($condition, $orderBy = [], $lockOption = '')
 * @method static Model_Orm_OrderSystem[] findAll($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static Generator|Model_Orm_OrderSystem[] yieldAll($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static Model_Orm_OrderSystem findOneFromRdview($condition, $orderBy = [], $lockOption = '')
 * @method static findRowFromRdview($columns, $condition, $orderBy = [])
 * @method static Model_Orm_OrderSystem[] findAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static findRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findValueFromRdview($column, $cond, $orderBy = [])
 * @method static findFromRdview($cond = [])
 * @method static findBySqlFromRdview($sql, $asArray = true)
 * @method static countFromRdview($cond, $column = '*')
 * @method static existsFromRdview($cond)
 * @method static Generator|Model_Orm_OrderSystem[] yieldAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
*/

class Model_Orm_OrderSystem extends Orderui_Base_Orm
{

    public static $tableName = 'order_system';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';

    /**
     * 通过业态订单号获取Oms订单信息
     * @param  int $intBusinessOrderId
     * @return mixed
     */
    public static function getOrderInfoByBusinessOrderId($intBusinessOrderId)
    {
        $arrCondition = [
            'business_form_order_id' => $intBusinessOrderId,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }

    /**
     * 通过oms订单号获取Oms订单信息
     * @param  integer $intOmsOrderId
     * @return array
     */
    public static function getOrderInfoByOmsOrderId($intOmsOrderId)
    {
        $arrCondition = [
            'order_system_id' => $intOmsOrderId,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }
}
