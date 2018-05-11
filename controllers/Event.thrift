namespace php orderui
namespace java me.ele.orderui
#OMS 异常
exception OrderuiUserException {
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

#确认入库事件参数
struct ConfirmStockinOrderInfo {
    1:required i32 stockin_order_id,
    2:optional string stockin_order_remark,
    3:required string sku_info_list
}

# 司机信息
struct DriverInfo {
    1:required i8 sex,
    2:required string name,
    3:required string contact_phone,
    4:required string ID
}

# 同步司机信息参数
struct SyncDriverInfo {
    1:required i32 shipment_order_id,
    2:required DriverInfo driver_info,
}


#服务定义
service EventService {
    EventData triggerEvent(1:required EventInfo objEventInfo)
        throws (1: OrderuiUserException OrderuiUserException),
    EventData confirmStockinOrder(1:required ConfirmStockinOrderInfo objData)
        throws (1: OrderuiUserException OrderuiUserException),
    EventData deliveryOrder(1:string stockout_order_id)
        throws (1: OrderuiUserException OrderuiUserException),
    EventData syncDriverInfo(1:required SyncDriverInfo objSyncDriverInfo)
        throws (1: OrderuiUserException OrderuiUserException),
    EventData syncShiomentOrderStatus(1:required i32 shipmentOrderId, 2:required i8 orderStatus)
        throws (1: OrderuiUserException OrderuiUserException),
}

