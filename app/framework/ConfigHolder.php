<?php
/**
 * Class for working with getting information about configuration
 */

namespace framework;

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