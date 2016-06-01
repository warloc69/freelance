<?php
/**
 * File described class accessing to config described in app/config.ini
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * ConfigHolder class for accessing to config described in app/config.ini
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class ConfigHolder
{
    private static $info = array();

    /**
     * load information from ..\app\config.ini
     * must be called befor using
     */
    public static function load()
    {
        self::$info = parse_ini_file("..\\app\\config.ini");
        if(!self::$info)
            self::$info = parse_ini_file("../app/config.ini");
    }

    /**
     * returns config by name
     * must be called after load()
     */
    public static function getConfig($name)
    {
        return self::$info[$name];
    }
}