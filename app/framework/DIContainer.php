<?php
/**
 * File described DI container class 
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * DIContainer class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class DIContainer
{
    private static $templates = null;
    private static $services = null;

    /**
     * DIContainer constructor.
     */
    function __construct()
    {
        if (self::$templates == null) {
            self::$services  = array();
            self::$templates = array();
            $routsConfig     = file_get_contents("..\\app\\di_config.json");
            if(!$routsConfig) {
                $routsConfig     = file_get_contents("../app/di_config.json");
            }
            self::$templates = json_decode($routsConfig, true);
        }
    }

    /**
     * Function returns service accordingly to config app/di_config.json
     * 
     * @param $service string name of service
     */
    function get($service)
    {
        $constructor = self::$templates[$service]["constructor"];
        if (count(self::$services) != 0 && in_array($service, self::$services)) {
            return self::$services[$service];
        }

        $args     = self::$templates[$service]["arguments"];
        $initArgs = array();
        foreach ($args as $arg) {
            if (count(self::$services) == 0 || !in_array($arg, self::$services)) {
                self::$services[$arg] = $this->get($arg);
            }
            $initArgs[$arg] = self::$services[$arg];
        }

        $r                        = new \ReflectionClass($constructor);
        self::$services[$service] = $r->newInstanceArgs($initArgs);
        return self::$services[$service];
    }
}