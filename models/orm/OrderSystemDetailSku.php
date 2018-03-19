<?php

/**
 * @property int $id
 * @property int $order_system_detail_id
 * @property int $order_id
 * @property int $sku_id
 * @property int $sku_amount
 * @property string $sku_ext
 * @property string $sku_exception
 * @property int $create_time
 * @property int $update_time
 * @property int $is_delete
 * @property int $version
 * @method static Model_Orm_OrderSystemDetailSku findOne($condition, $orderBy = [], $lockOption = '')
 * @method static Model_Orm_OrderSystemDetailSku[] findAll($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static Generator|Model_Orm_OrderSystemDetailSku[] yieldAll($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static Model_Orm_OrderSystemDetailSku findOneFromRdview($condition, $orderBy = [], $lockOption = '')
 * @method static findRowFromRdview($columns, $condition, $orderBy = [])
 * @method static Model_Orm_OrderSystemDetailSku[] findAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static findRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findValueFromRdview($column, $cond, $orderBy = [])
 * @method static findFromRdview($cond = [])
 * @method static findBySqlFromRdview($sql, $asArray = true)
 * @method static countFromRdview($cond, $column = '*')
 * @method static existsFromRdview($cond)
 * @method static Generator|Model_Orm_OrderSystemDetailSku[] yieldAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
*/

class Model_Orm_OrderSystemDetailSku extends Orderui_Base_Orm
{

    public static $tableName = 'order_system_detail_sku';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';

    /**
     * 批量创建order system detail sku记录
     * @param array   $arrSkuList
     */
    public static function batchInsertSkuInfo($arrSkuList)
    {
        self::batchInsert($arrSkuList);
    }
}
