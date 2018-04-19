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

#事件信息
struct EventInfo {
    1:required i32 client_id,
    2:required string event_key,
    3:required string data
}
struct typeExample1 {
    1:required string paramExample1
    2:required i32 paramExample2
}
#服务定义
service EventService {
    Data triggerEvent(1:required EventInfo objEventInfo)
        throws (1: OrderUserException userException)
    Data confirmStockinOrder(1:required ConfirmStockinOrderInfo objData)
        throws (1: OrderUserException userException)


}
