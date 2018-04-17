<?php
/**
 * @name Orderui_Define_Event
 * @desc 事件与系统的对应关系
 * @author huabang.xue@ele.me
 */
class Orderui_Define_Event
{
    /*
     * 彩云
     */
    const CLIENT_NSCM  = 1;
    /*
     * 沧海
     */
    const CLIENT_NWMS  = 2;
    /*
     * OMS
     */
    const CLIENT_OMS   = 3;
    /*
     * 轻舟
     */
    const CLIENT_TMS   = 4;
    /*
     * 无人货架 ele NOW
     */
    const CLIENT_ELENOW = 5;
    /*
     * 飞阁
     */
    const CLIENT_STORE = 6;
    /*
     * 前置仓
     */
    const CLIENT_PRE_WAREHOUSE = 7;
    /*
     * 系统列表
     */
    const CLIENT_LIST = [
        self::CLIENT_NSCM => '彩云',
        self::CLIENT_NWMS => '沧海',
        self::CLIENT_OMS  => 'OMS',
        self::CLIENT_TMS  => '轻舟',
        self::CLIENT_ELENOW => '无人货架',
        self::CLIENT_STORE  => '飞阁',
        self::CLIENT_PRE_WAREHOUSE => '前置仓',
    ];

    /*
     * 系统与事件对应关系列表
     */
    const CLIENT_EVENT_LIST = [
        self::CLIENT_NSCM => self::NSCM_EVENT_LIST,
        self::CLIENT_NWMS => self::NWMS_EVENT_LIST,
        self::CLIENT_OMS  => self::OMS_EVENT_LIST,
        self::CLIENT_TMS  => self::TMS_EVENT_LIST,
        self::CLIENT_ELENOW => self::ELENOW_EVENT_LIST,
        self::CLIENT_STORE  => self::STORE_EVENT_LIST,
        self::CLIENT_PRE_WAREHOUSE => self::PRE_WAREHOUSE_EVENT_LIST,
    ];

    //签收运单事件
    const EVENT_SIGNUP_SHIPMENT_ORDER = 'signupShipmentOrder';
    //增量更新Oms子订单信息
    const EVENT_UPDATE_OMS_ORDER_INFO = 'UpdateOmsOrderInfo';

    /*
     * 彩云接入事件列表
     */
    const NSCM_EVENT_LIST = [

    ];
    /*
     * 沧海接入事件列表
     */
    const NWMS_EVENT_LIST = [
        self::EVENT_UPDATE_OMS_ORDER_INFO => '增量更新Oms子订单信息',
    ];
    /*
     * OMS接入事件列表
     */
    const OMS_EVENT_LIST = [

    ];
    /*
     * 轻舟接入事件列表
     */
    const TMS_EVENT_LIST = [

    ];
    /*
     * 无人货架接入事件列表
     */
    const ELENOW_EVENT_LIST = [
        self::EVENT_SIGNUP_SHIPMENT_ORDER => '签收运单事件',
    ];
    /*
     * 飞阁接入事件列表
     */
    const STORE_EVENT_LIST = [
        self::EVENT_SIGNUP_SHIPMENT_ORDER => '签收运单事件',
    ];
    /*
     * 前置仓接入事件列表
     */
    const PRE_WAREHOUSE_EVENT_LIST = [

    ];

    /**
     * branch 1
     */
    const BRANCH_1 = 1;

    /**
     * branch 2
     */
    const BRANCH_2 = 2;

    /**
     * branch 3
     */
    const BRANCH_3 = 3;

    /**
     * branch 4
     */
    const BRANCH_4 = 4;

    /**
     * branch 5
     */
    const BRANCH_5 = 5;

    /**
     * branch 6
     */
    const BRANCH_6 = 6;

    /**
     * call ral
     */
    const CALL_RAL = 1;

    /**
     * call wrpc
     */
    const CALL_WRPC = 2;
}