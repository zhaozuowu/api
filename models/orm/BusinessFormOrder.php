<?php

/**
 * @property int $id
 * @property int $business_form_order_id
 * @property int $business_form_order_type
 * @property string $business_form_order_remark
 * @property string $business_form_ext
 * @property string $business_form_order_exception
 * @property int $customer_city_id
 * @property string $customer_city_name
 * @property string $customer_id
 * @property string $customer_name
 * @property string $customer_contactor
 * @property string $customer_contact
 * @property string $customer_address
 * @property int $version
 * @property int $status
 * @property int $create_time
 * @property int $process_time
 * @property int $update_time
 * @property int $is_delete
 * @method static Model_Orm_BusinessFormOrder findOne($condition, $orderBy = [], $lockOption = '')
 * @method static Model_Orm_BusinessFormOrder[] findAll($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static Generator|Model_Orm_BusinessFormOrder[] yieldAll($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static Model_Orm_BusinessFormOrder findOneFromRdview($condition, $orderBy = [], $lockOption = '')
 * @method static findRowFromRdview($columns, $condition, $orderBy = [])
 * @method static Model_Orm_BusinessFormOrder[] findAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static findRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findValueFromRdview($column, $cond, $orderBy = [])
 * @method static findFromRdview($cond = [])
 * @method static findBySqlFromRdview($sql, $asArray = true)
 * @method static countFromRdview($cond, $column = '*')
 * @method static existsFromRdview($cond)
 * @method static Generator|Model_Orm_BusinessFormOrder[] yieldAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
*/

class Model_Orm_BusinessFormOrder extends Wm_Orm_ActiveRecord
{

    public static $tableName = 'business_form_order';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';
}
