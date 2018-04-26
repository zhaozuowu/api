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
#物流单签收信息
struct SignupInfo {
    1:required i32 stockout_order_id,
    2:required list<SkuInfo> skus_event,
    3:optional i32 biz_type
}
#运单拒收信息
struct ShipmentOrderInfo {
    1:required string shipment_order_id,
    2:required list<map<string,string>> reject_skus,
    3:optional i32 biz_type
}
#服务定义
service ShipmentService {
    Data signup(1:required SignupInfo objSignupInfo)
        throws (1: OrderUserException userException),
    Data createReverseBusinessFormOrder(1:required ShipmentOrderInfo objShipmentOrderInfo)
        throws (1: OrderUserException userException)
}
