<?php
/**
 * @name Action_Recv
 * @desc receive event trigger
 * @author bochao.lv@ele.me
 */

class Action_Recv extends Wm_Lib_Wmq_CommitAction
{
    /**
     * page service map
     * @var array
     */
    protected $_map_ps = [
        'Eventsystem' => 'Service_Page_Ens_Commit',
    ];
}