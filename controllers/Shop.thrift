namespace php orderui
namespace java me.ele.orderui
#创建业态订单返回异常
exception OrderUserException {
    1: string cl, #错误分类
    2: string msg, #错误原因
    3: map<string, string> fields, #包含错误信息
    4: string type
}
#返回值
struct Data {
    1:required bool result
}
#签收sku信息
struct SkuInfo {
    1:required i32 sku_id,
    2:required i32 order_amount,
    3:required i32 event_type
}
#退货sku信息
struct ReturnSkuInfo {
    1:required i32 sku_id,
    2:required i32 order_amount
}
#出库单签收信息
struct SignupInfo {
    1:required i32 stockout_order_id,
    2:required list<SkuInfo> skus_event,
    3:optional i32 biz_type
}
#退货单信息
struct ReturnOrderInfo {
    1:required i32 logistics_order_id,
    2:required i32 business_form_order_type,
    3:optional string business_form_order_remark,
    4:required list<ReturnSkuInfo> skus,
}
#服务定义
service ShopService {
    Data signup(1:required SignupInfo objSignupInfo)
        throws (1: OrderUserException userException),
    Data createShopReturnOrder(1:required ReturnOrderInfo objReturnOrderInfo)
        throws (1: OrderUserException userException)
}
