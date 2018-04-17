<?php
Bd_Init::init();
ini_set('memory_limit','1G');
try{
$serviceName = new Bd_Wrpc_Client("bdwaimai_earthnet.oms",'orderui','ShipmentService');
$param = [
    'shipment_order_id' => 1521038428,
    'signup_status'     => 1,
    'signup_skus' => ['1000486' => '2'],
];
$objBaz = $serviceName->signupShipmentOrder($param);
var_dump($objBaz);exit();
}catch(exception $e){
var_dump($e);
die;
}
