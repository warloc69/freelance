<?php
namespace framework;

/**
 * class for routing requests
 */
class Route
{
    private $service = null;
    private $action = null;

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

    function getService()
    {
        return $this->service;
    }

    function getAction()
    {
        return $this->action;
    }

}