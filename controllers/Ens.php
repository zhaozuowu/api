<?php
/**
 * @name Controller_Ens
 * @desc wmq
 * @author bochao.lv@ele.me
 */
class Controller_Ens extends Ap_Controller_Abstract
{
    /**
     * action name
     * @var string
     */
    const ACTION_NAME = 'Action_Ens_Recv';

    /**
     * action path
     * @var string
     */
    const ACTION_PATH = 'actions/ens/Recv.php';


    /**
     * real run action
     */
    private function runAction()
    {
        $arrMap = [
            self::ACTION_NAME => APP_PATH . DIRECTORY_SEPARATOR . Bd_AppEnv::getCurrApp() . DIRECTORY_SEPARATOR . self::ACTION_PATH,
        ];
        Bd_Autoloader::addClassMap($arrMap);
        $class = self::ACTION_NAME;
        $action = new $class($this->_request, $this->_response, $this->_view);
        $action->execute();
    }

    /**
     * router
     */
    public function recv1Action()
    {
        return $this->runAction();
    }

    /**
     * router
     */
    public function recv2Action()
    {
        return $this->runAction();
    }

    /**
     * router
     */
    public function recv3Action()
    {
        return $this->runAction();
    }

    /**
     * router
     */
    public function recv4Action()
    {
        return $this->runAction();
    }

    /**
     * router
     */
    public function recv5Action()
    {
        return $this->runAction();
    }

    /**
     * router
     */
    public function recv6Action()
    {
        return $this->runAction();
    }
}
