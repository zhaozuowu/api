<?php
/**
 * @name Order_Define_Wrpc
 * @desc order define wrpc
 * @author jinyu02@iwaimai.baidu.com
 */

class Orderui_Define_Wrpc
{
    /**
     * nwms app id
     * @var string
     */
    const NWMS_APP_ID = 'bdwaimai_earthnet.nwms';

    /**
     * nwms namespace
     * @var string
     */
    const NWMS_NAMESPACE = 'order';

    /**
     * nwms service name
     * @var string
     */
    const NWMS_SERVICE_NAME = 'BusinessService';

    ////////////////////
    /// APP_ID_*
    ////////////////////
    const APP_ID_NWMS = 'bdwaimai_earthnet.nwms';
    const APP_ID_TMS = 'scm.tms_core';
    const APP_ID_OMS = 'bdwaimai_earthnet.oms';
    const APP_ID_SHOP = 'bdwaimai_iss.base';


    ////////////////////
    /// NAMESPACE_*
    ////////////////////
    const NAMESPACE_NWMS = 'order';
    const NAMESPACE_TMS = 'me.ele.scm.tms.shipment.api';
    const NAMESPACE_OMS = 'orderui';
    const NAMESPACE_SHOP = 'book';


    ////////////////////
    /// SERVICE_NAME_*
    ////////////////////
    const SERVICE_NAME_NWMS = 'BusinessService';
    const SERVICE_NAME_NWMS_STOCKIN = 'StockinService';
    const SERVICE_NAME_TMS = 'ShipmentService';
    const SERVICE_NAME_OMS = 'BusinessService';
    const SERVICE_NAME_SHOP = 'BookService';

    /**
     * tms app id
     * @var string
     */
    const TMS_APP_ID = 'scm.tms_core';

    /**
     * tms namespace
     * @var string
     */
    const TMS_NAMESPACE = 'me.ele.scm.tms.shipment.api';

    /**
     * tms service name
     * @var string
     */
    const TMS_SERVICE_NAME = 'ShipmentService';
}