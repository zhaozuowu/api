<?php
/**
 * @name Order_Define_BusinessFormOrder
 * @desc 业态出库订常量定义
 * @author  zhaozuowu@iwaimai.baidu.com
 */

class Orderui_Define_BusinessFormOrder
{
    /**
     * 用户授权介入校验
     * @var string
     */
    const SALT_VAL = 'HWDGRMY';

    /**
     * 业态订单创建成功
     * @var integer
     */
    const BUSINESS_FORM_ORDER_SUCCESS = 1;

    /**
     * 业态订单创建失败
     * @var integer
     */
    const BUSINESS_FORM_ORDER_FAILED = 2;

    /**
     * 业态订单状态列表
     * @var array
     */
    const BUSINESS_FORM_ORDER_STATUS_LIST = [
        '1' => '成功',
        '2' => '失败',
    ];

    /**
     * 无人货架
     * @var integer
     */
    const BUSINESS_FORM_ORDER_TYPE_SHELF = 1;

    /**
     * 前置仓
     * @var integer
     */
    const BUSINESS_FORM_ORDER_TYPE_PREPOSITION = 2;

    /**
     * 门店
     * @var integer
     */
    const BUSINESS_FORM_ORDER_TYPE_SHOP = 3;

    /**
     * 业态订单类型列表
     * @var array
     */
    const BUSINESS_FORM_ORDER_TYPE_LIST = [
        self::BUSINESS_FORM_ORDER_TYPE_SHELF => '无人货架',
        self::BUSINESS_FORM_ORDER_TYPE_PREPOSITION => '前置仓',
        self::BUSINESS_FORM_ORDER_TYPE_SHOP => '便利店',
    ];

    /**
     * 补货类型
     * @var integer
     */
    const ORDER_SUPPLY_TYPE_CREATE = 1;  //货架铺货
    const ORDER_SUPPLY_TYPE_SUPPLY = 2;  //货架补货
    const ORDER_SUPPLY_TYPE_ORDER   = 4; //门店订货
    const ORDER_SUPPLY_TYPE_REJECT  = 3; //门店退货
    const ORDER_SUPPLY_TYPE_RETREAT = 5; //货架撤点

    /**
     * 货架正向补货类型
     * @var integer
     */
    const SHELF_ORDER_OBVESER_SUPPLY_TYPE = [
        self::ORDER_SUPPLY_TYPE_CREATE,
        self::ORDER_SUPPLY_TYPE_SUPPLY,
    ];

    /**
     * 补货类型
     * @var array
     */
    const ORDER_SUPPLY_TYPE = [
        self::ORDER_SUPPLY_TYPE_CREATE => '铺货',
        self::ORDER_SUPPLY_TYPE_SUPPLY => '补货',
        self::ORDER_SUPPLY_TYPE_RETREAT => '撤点',
        self::ORDER_SUPPLY_TYPE_ORDER   => '订货',
        self::ORDER_SUPPLY_TYPE_REJECT  => '退货',
    ];

    /**
     * 携带设备类型为货架
     * @var integer
     */
    const ORDER_DEVICE_TYPE_SHELF = 0;

    /**
     * 携带设备类型为冷柜
     * @var integer
     */
    const ORDER_DEVICE_TYPE_REFRIGERATOR = 1;

    /**
     * 携带设备类型为双货架
     * @var integer
     */
    const ORDER_DEVICE_TYPE_DOUBLE = 2;

    /**
     * 携带设备类型为小货架
     * @var integer
     */
    const ORDER_DEVICE_TYPE_SMALL_SHELF = 3;

    /**
     * 携带设备类型为小冷柜
     * @var integer
     */
    const ORDER_DEVICE_TYPE_SMALL_REFRIGERATOR = 4;

    /**
     * 携带设备类型为鲜食柜
     * @var integer
     */
    const ORDER_DEVICE_TYPE_FRESH = 5;

    /**
     * 携带设备类型
     * @var array
     */
    const ORDER_DEVICE_MAP = [
        self::ORDER_DEVICE_TYPE_SHELF => '货架',
        self::ORDER_DEVICE_TYPE_REFRIGERATOR => '冰柜',
        self::ORDER_DEVICE_TYPE_REFRIGERATOR => '冷柜',
        self::ORDER_DEVICE_TYPE_DOUBLE => '双货架',
        self::ORDER_DEVICE_TYPE_SMALL_SHELF => '小货架',
        self::ORDER_DEVICE_TYPE_SMALL_REFRIGERATOR => '小冷柜',
        self::ORDER_DEVICE_TYPE_FRESH => '鲜食柜',
    ];

    /**
     * 最小纬度
     * @var float
     */
    const MIN_LATITUDE = 3.86;

    /**
     * 最大纬度
     * @var float
     */
    const MAX_LATITUDE = 53.55;

    /**
     * 最小经度
     * @var float
     */
    const MIN_LONGITUDE = 73.66;

    /**
     * 最大经度
     * @var float
     */
    const MAX_LONGITUDE = 135.05;

    /**
     * 高德地图坐标标识
     * @var integer
     */
    const CUSTOMER_LOCATION_SOURCE_AMAP = 1;

    /**
     * 百度地图坐标标识
     * @var integer
     */
    const CUSTOMER_LOCATION_SOURCE_BAIDU = 2;

    /**
     * 地图来源标识列表
     * @var array
     */
    const CUSTOMER_LOCATION_SOURCE_TYPE = [
        self::CUSTOMER_LOCATION_SOURCE_AMAP => '高德',
        self::CUSTOMER_LOCATION_SOURCE_BAIDU => '百度',
    ];

    /**
     * 正向的
     * @var integer
     */
    const ORDER_WAY_OBVERSE = 1;

    /**
     * 反向的
     * @var integer
     */
    const ORDER_WAY_REVERSE = 2;


    /**
     * 取消失败文案
     * @var string
     */
    const OMS_CANCEL_FAILED_MESSAGE = '%s，如需取消请线下联系沧海及轻舟人员使用手动取消功能';

    /**
     * 取消运单失败文案
     * @var string
     */
    const OMS_CANCEL_SHIPMENT_ORDER_FAILED = '取消运单失败，如需取消请线下联系沧海及轻舟人员使用手动取消功能';
    /**
     * 取消运单失败文案
     * @var string
     */
    const OMS_CANCEL_BACK_SHIPMENT_ORDER_FAILED = '运单已排线不可取消，如需取消请线下联系轻舟人员使用手动取消功能';

    /**
     * 仓库分配失败文案
     * @var string
     */
    const OMS_WAREHOUSE_DISTRIBUTE_FAILED = '【仓库信息异常】网点没有对应的仓库';
}