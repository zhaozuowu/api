<?php
/**
 * @name OrderRouter.php
 * @desc OrderRouter.php
 * @author yu.jin03@ele.me
 */
class Service_Data_OrderRouter
{
    /**
     * @var Orderui_Base_OrderRouter
     */
    protected static $objOrderRouter;

    /**
     * 执行拆分转发路由
     * @param $arrBusinessFormOrderInfo
     * @return array|mixed
     * @throws Orderui_BusinessError
     */
    public static function execute($arrBusinessFormOrderInfo)
    {
        //根据业态类型初始化不同路由
        switch ($arrBusinessFormOrderInfo['business_form_order_type']) {
            case Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_SHELF:
                    static::initShelfOrderRouter($arrBusinessFormOrderInfo);
                    break;
            case Orderui_Define_BusinessFormOrder::BUSINESS_FORM_ORDER_TYPE_SHOP:
                    static::initShopOrderRouter($arrBusinessFormOrderInfo);
                    break;
            default:
                    break;
        }
        if (!empty(static::$objOrderRouter)) {
            return static::$objOrderRouter->createOrder($arrBusinessFormOrderInfo);
        }
        return [];
    }

    /**
     * 初始化货架拆分策略
     * @param $arrBusinessFormOrderInfo
     */
    protected static function initShelfOrderRouter($arrBusinessFormOrderInfo)
    {
        if (Orderui_Define_BusinessFormOrder::ORDER_WAY_OBVERSE
            == $arrBusinessFormOrderInfo['business_form_order_way']) {
            static::$objOrderRouter = new Service_Data_Router_ShelfObverse();
        } else {
            static::$objOrderRouter = new Service_Data_Router_ShelfReverse();
        }
    }

    /**
     * 初始化门店拆分策略
     * @param $arrBusinessFormOrderInfo
     */
    protected static function initShopOrderRouter($arrBusinessFormOrderInfo)
    {
        if (Orderui_Define_BusinessFormOrder::ORDER_WAY_OBVERSE
            == $arrBusinessFormOrderInfo['business_form_order_way']) {
            static::$objOrderRouter = new Service_Data_Router_ShopObverse();
        }
    }

}