<?php
/**
 * File described ClassLoader class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * ClassLoader class load class accordingly to mapping in app/class_loader_config.json
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class ClassLoader
{
    private $classes = null;
    private $default = "../app/framework/";

    /**
     * ClassLoader constructor.
     */
    public function __construct()
    {
        $routsConfig   = file_get_contents("..\\app\\class_loader_config.json");
        if(!$routsConfig) {
            $routsConfig   = file_get_contents("../app/class_loader_config.json");
        }
        $this->classes = json_decode($routsConfig, true);
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * callback function used for autoloading
     * @param $class class name
     */
    private function autoload($class)
    {
        $className = ltrim($class, '\\');
        $file      = null;
        if ($lastNsPos = strrpos($className, '\\')) {
            $ns        = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
        }
        if (isset($this->classes[$ns]) && !empty($this->classes[$ns]) && $this->classes[$ns] != null) {
            $pathItem = $this->classes[$ns];
            $file     = $pathItem["path"].$className.'.php';
        } else {
            $file = $this->default.$className.'.php';
        }
        if (file_exists($file)) {
            include $file;
        } else {
            throw new \Exception("file not found" . $file);
        }
    }
}

new \framework\ClassLoader();

