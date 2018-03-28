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
    1:required string shipment_order_id,
    2:required bool result
}

#运单签收信息
struct ShipmentOrderInfo {
    1:required string shipment_order_id,
    2:required i32 signup_status,
    3:required list<map<string,string>> signup_skus,
    4:optional list<map<string,string>> offshelf_skus,
    5:optional list<string> adjust_skus
}
#服务定义
service ShipmentService {
    Data signupShipmentOrder(1:required ShipmentOrderInfo objShipmentOrderInfo)
        throws (1: OrderUserException userException)
}
