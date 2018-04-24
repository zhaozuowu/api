namespace php orderui
namespace java me.ele.orderui
#OMS 异常
exception OmsException {
    1: string cl,
    2: string msg,
    3: map<string, string> fields,
    4: string type
}

#返回值
struct EventData {
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
    EventData triggerEvent(1:required EventInfo objEventInfo)
        throws (1: OmsException omsException)
    EventData confirmStockinOrder(1:required ConfirmStockinOrderInfo objData)
        throws (1: OmsException omsException)
    EventData deliveryOrder(1:string stockout_order_id)
        throws (1: OmsException omsException)

}

