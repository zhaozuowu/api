<?php
/**
 * @name Controller_Main
 * @desc 主控制器,也是默认控制器
 * @author yu.jin03@ele.me
 */
class Controller_Main extends Ap_Controller_Abstract {
	public $actions = array(
        'splitbusinessorder' => 'actions/SplitBusinessOrder.php', #创建nwms-order#
	);
}
