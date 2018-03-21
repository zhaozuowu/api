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

class Model_Orm_BusinessFormOrder extends Orderui_Base_Orm
{

    public static $tableName = 'business_form_order';
    public static $dbName = 'oms_order';
    public static $clusterName = 'oms_orderui_cluster';

    /**
     * 获取业态订单信息
     * @param $arrConditions
     * @param array $arrColumns
     * @param null $intOffset
     * @param null $intLimit
     * @return array
     */
    public static function getBusinessFormOrderListByConditions($arrConditions, $arrColumns = [], $intOffset = null, $intLimit = null)
    {
        if (empty($arrColumns)) {
            $arrColumns = self::getAllColumns();
        }
        return self::findRows($arrColumns, $arrConditions, ['create_time' => 'desc'], $intOffset, $intLimit);
    }


    /**
     * 根据出库单号获取出库单信息
     * @param $strOrderId 业态订单号
     * @return array
     */
    public static function getBusinessFormOrderByOrderId($strOrderId)
    {
        Bd_Log::debug(__METHOD__ . ' called, input params: ' . json_encode(func_get_args()));
        $strOrderId = empty($strOrderId) ? 0 : intval($strOrderId);
        if (empty($strOrderId)) {
            return [];
        }
        $condition = ['business_form_order_id' => $strOrderId];
        $arrList = self::findOne($condition);
        if (empty($arrList)) {
            return [];
        }
        $arrList = $arrList->toArray();
        Bd_Log::debug(__METHOD__ . ' return: ' . json_encode($arrList));
        return $arrList;
    }

    /**
     * 通过上游订单号获取业态订单信息
     * @param  integer $intSourceOrderId
     * @return Model_Orm_BusinessFormOrder
     */
    public static function getOrderInfoBySourceOrderId($intSourceOrderId)
    {
        $arrCondition = [
            'source_order_id' => $intSourceOrderId,
            'is_delete' => Orderui_Define_Const::NOT_DELETE,
        ];
        return self::findRow(self::getAllColumns(), $arrCondition);
    }
}
