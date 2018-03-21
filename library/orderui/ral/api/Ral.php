<?php
/**
 * @name Orderui_Ral_Api_Ral
 * @desc Orderui_Ral_Api_Ral
 * @author hang.song02@ele.me
 */

class Orderui_Ral_Api_Ral extends Orderui_ApiRaler
{
    public function defaultFormat($data, $name)
    {
        return $data;
    }
}