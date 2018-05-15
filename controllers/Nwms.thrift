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

#商品效期和数量
struct SkuDistributeInfo {
    1:required i32 expire_date,
    2:required i32 amount
}

#商品签收信息
strurct SkuInfo {
    1:required i32 sku_id,
    2:required list<SkuDistributeInfo> distribute_info
}

#揽收信息
struct AcceptedSkuInfo {
    1:required string logistic_order_id,
    2:required string shipment_order_id,
    3:required list<SkuInfo> sku_info
}

#服务定义
service NwmsService {
    Data syncAcceptStockinOrderSkuInfo(1:required AcceptedSkuInfo objAcceptedSkuInfo)
        throws (1: OrderUserException userException)
}