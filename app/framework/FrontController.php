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

use controller\DefaultController;

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
    private static $instance;
    
    private function __clone(){}

    private function __wakeup() {}

    /**
     * @return FrontController 
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * FrontController constructor.
     */
    private function __construct()
    {
    }

    /**
     * init FrontController
     */
    public function run()
    {
        try{
            $this->di = new \framework\DIContainer();
            ConfigHolder::load();
            $rout          = $this->di->get('router');
            $this->service = $rout->getService();
            $this->action  = $rout->getAction();
            $this->execute();
        } catch(\Exception $e){
            (new DefaultController())->serverError();
        }
    }

    /**
     *  function execute action from request
     */
    private function execute()
    {
        if ($this->service != null && $this->action != null) {
            $service = $this->di->get($this->service);
            call_user_func(array($service, $this->action));
        }
    }

}