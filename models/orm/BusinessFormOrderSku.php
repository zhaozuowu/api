<?php

/**
 * @property int $id
 * @property int $business_form_order_id
 * @property int $sku_id
 * @property int $sku_amount
 * @property string $sku_ext
 * @property string $sku_exception
 * @property int $create_time
 * @property int $update_time
 * @property int $is_delete
 * @property int $version
 * @method static Model_Orm_BusinessFormOrderSku findOne($condition, $orderBy = [], $lockOption = '')
 * @method static Model_Orm_BusinessFormOrderSku[] findAll($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static Generator|Model_Orm_BusinessFormOrderSku[] yieldAll($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static Model_Orm_BusinessFormOrderSku findOneFromRdview($condition, $orderBy = [], $lockOption = '')
 * @method static findRowFromRdview($columns, $condition, $orderBy = [])
 * @method static Model_Orm_BusinessFormOrderSku[] findAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null, $with = [])
 * @method static findRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static findValueFromRdview($column, $cond, $orderBy = [])
 * @method static findFromRdview($cond = [])
 * @method static findBySqlFromRdview($sql, $asArray = true)
 * @method static countFromRdview($cond, $column = '*')
 * @method static existsFromRdview($cond)
 * @method static Generator|Model_Orm_BusinessFormOrderSku[] yieldAllFromRdview($cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldRowsFromRdview($columns, $cond, $orderBy = [], $offset = 0, $limit = null)
 * @method static yieldColumnFromRdview($column, $cond, $orderBy = [], $offset = 0, $limit = null)
*/

class Model_Orm_BusinessFormOrderSku extends Wm_Orm_ActiveRecord
{

    public static $tableName = 'business_form_order_sku';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';
<<<<<<< HEAD

    /**
     * 获取业态订单sku信息
     * @param $arrConditions
     * @param array $arrColumns
     * @param null $intOffset
     * @param null $intLimit
     * @return array
     */
    public static function getBusSkuListByConditions($arrConditions, $arrColumns = [], $intOffset = null, $intLimit = null)
    {
        if (empty($arrColumns)) {
            $arrColumns = self::getAllColumns();
        }
        return self::findRows($arrColumns, $arrConditions, ['create_time' => 'desc'], $intOffset, $intLimit);
    }
=======
>>>>>>> b3b84bd31b3b031e559645a50afee857b07b2449
}
