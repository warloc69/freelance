<?php
/**
 * File described Route class for for parsing routs
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 *  Route class for parsing routs accordingly to /app/routs_config.json
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class Route
{
    private $service = null;
    private $action = null;

    /**
     * Route constructor.
     *
     * @param $request
     */
    function __construct($request)
    {
        $routsConfig = file_get_contents("..\\app\\routs_config.json");
        if(!$routsConfig) {
            $routsConfig     = file_get_contents("../app/routs_config.json");
        }
        $rconfig     = json_decode($routsConfig, true);
        $routs       = $rconfig[$request->getMethod()];
        foreach ($routs as $key => $val) {
            if (preg_match($key, $request->getUri()) === 1) {
                $this->service = $val["service"];
                $this->action  = $val["action"];
            }
        }
    }

    /**
     * @return service name
     */
    function getService()
    {
        return $this->service;
    }

    /**
     * @return action 
     */
    function getAction()
    {
        return $this->action;
    }

}