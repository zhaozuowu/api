namespace php orderui
namespace java me.ele.orderui
#创建业态订单返回异常
exception OrderuiUserException {
    1: string cl, #错误分类
    2: string msg, #错误原因
    3: map<string, string> fields, #包含错误信息
    4: string type
}
#返回商品信息
struct RetSkuInfo {
    1:required string sku_id,
    2:required i32 cost_price_tax,
    3:required i32 cost_price_untax,
    4:required i32 order_amount,
    5:required i32 distribute_amount
}
#返回值
struct Data {
    1:required string stockout_order_id,
    2:required list<RetSkuInfo> skus
}
#业态订单sku信息
struct BusinessFormOrderSku {
    1:required string sku_id,
    3:required i32 order_amount
}
#业态订单sku事件
struct BusinessFormOrderSkuEvent {
    1:required string sku_id,
    2:required i32 order_amount,
    3:required i32 event_type,
}
#货架信息
struct ShelfInfo {
    1:required i32 supply_type,
    2:required map<string, i32> devices
}
#业态订单信
struct BusinessFormOrderInfo {
    1:required string logistics_order_id,
    2:required ShelfInfo shelf_info,
    3:required i32 business_form_order_type,
    4:required i32 order_supply_type,
    5:optional string business_form_order_remark,
    6:required string customer_id,
    7:required string customer_name,
    8:required string customer_contactor,
    9:required string customer_contact,
    10:required string customer_address,
    11:required string customer_location,
    12:required i32 customer_location_source,
    13:required i32 customer_city_id,
    14:required string customer_city_name,
    15:required i32 customer_region_id,
    16:required string customer_region_name,
    17:required string executor,
    18:required string executor_contact,
    19:required map<string, i64> expect_arrive_time,
    20:required list<BusinessFormOrderSku> skus,
    21:required string business_form_token,
    22:required string business_form_key,
    23:required list<BusinessFormOrderSkuEvent> skus_event,
}
#货架撤点sku信息
struct ShelfRecallSkuInfo {
    1:required i32 sku_id,
    2:required i32 display_x,
    3:required i32 display_y,
    4:required i32 return_amount
}

#货架撤点sku信息
struct ReverseBusinessFormOrderSkus {
    1:required i32 sku_id,
    4:required i32 return_amount
}
#货架设备信息
struct ShelfDeviceInfo {
    1:required string device_no,
    2:required i32 device_type
}
#货架撤点信息
struct ShelfRecallInfo {
    1:required ShelfDeviceInfo shelf_info,
    3:required list<ShelfRecallSkuInfo> skus
}
#网点信息
struct CustomerInfo {
    1:required string id,
    2:required string name,
    3:required string contactor,
    4:required string contact,
    5:required string address,
    6:required i32 location,
    7:required i32 location_source,
    8:required i32 city_id,
    9:required string city_name,
    10:required i32 region_id,
    11:required string region_name,
    12:required string executor,
    13:required string executor_contact
}
#货架撤点信息
struct ShelfRecallOrderInfo {
    1:required string logistics_order_id,
    2:required i32 business_form_order_type,
    3:required string business_form_order_remark,
    4:required i32 order_return_type,
    5:required list<ShelfRecallInfo> shelf_sku_list,
    6:required i32 city_id,
    7:required string city_name,
    8:required CustomerInfo customer_info
}
#业态订单盘点信息
struct BusinessFormBackOrderCheckInfo {
    1:required string logistics_order_id,
    2:required list<ShelfDeviceInfo> shelf_infos,
    3:required list<ReverseBusinessFormOrderSkus> skus,
}

#服务定义
service BusinessService {
    #创建正向业态订单
    Data createBusinessFormOrder(1:required BusinessFormOrderInfo objBusinessFormOrderInfo)
        throws (1: OrderuiUserException userException),
    #取消物流单
    i32 cancelLogisticsOrder(1:required string logistics_order_id, 2:required string cancelRemark)
        throws (1: OrderuiUserException userException),
    #取消撤点单
    i32 cancelLogisticsBackOrder(1:required string logistics_order_id, 2:required string cancelRemark)
        throws (1: OrderuiUserException userException),
    #创建撤点单
    i32 recallShelf(1:required ShelfRecallOrderInfo shelf_recallorder_info)
        throws (1: OrderuiUserException userException),
    #业态订单盘点
    i32 checkReverseBusinessFormOrder(1:required BusinessFormBackOrderCheckInfo objBusinessFormOrderInfo)
        throws (1: OrderuiUserException userException)
}
