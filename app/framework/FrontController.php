<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 19:15
 */

namespace framework;

class FrontController
{
    private $service = null;
    private $action = null;
    private $di = null;

    function __construct()
    {
        $this->di = new \framework\DIContainer();
        ConfigHolder::load();
        $rout          = $this->di->get('router');
        $this->service = $rout->getService();
        $this->action  = $rout->getAction();
        $this->execut();
    }

    function execut()
    {
        if ($this->service != null && $this->action != null) {
            $service = $this->di->get($this->service);
            call_user_func(array($service, $this->action));
        }
    }

}