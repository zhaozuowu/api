<?php
/**
 * @name Orderui_Base_Struct
 * @desc Orderui_Base_Struct
 * @author bochao.lv@ele.me
 */

abstract class Orderui_Base_Struct
{
    private $arrParams;

    protected $arrProperty;

    function __construct(...$params)
    {
        $this->arrParams = [];
        $intCountInput = count($params);
        $intCountProperty = count($this->arrProperty);
        if ($intCountInput < $intCountProperty) {
            trigger_error('input params is less than needle', E_USER_WARNING);
        }
        for ($i = 0; $i < $intCountProperty; $i++) {
            if ($i < $intCountInput) {
                $this->arrParams[$this->arrProperty[$i]] = $params[$i];
            } else {
                $this->arrParams[$this->arrProperty[$i]] = null;
            }
        }
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->arrParams)) {
            return $this->arrParams[$name];
        } else {
            return null;
        }
    }

    function __set($name, $value)
    {
        if (array_key_exists($name, $this->arrParams)) {
            $this->arrParams[$name] = $value;
        }
    }
}