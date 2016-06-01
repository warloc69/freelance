<?php
/**
 * File described FrontController class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * FrontController class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class FrontController
{
    private $service = null;
    private $action = null;
    private $di = null;

    /**
     * FrontController constructor.
     */
    function __construct()
    {
        $this->di = new \framework\DIContainer();
        ConfigHolder::load();
        $rout          = $this->di->get('router');
        $this->service = $rout->getService();
        $this->action  = $rout->getAction();
        $this->execut();
    }

    /**
     *  function execute action from request
     */
    function execut()
    {
        if ($this->service != null && $this->action != null) {
            $service = $this->di->get($this->service);
            call_user_func(array($service, $this->action));
        }
    }

}