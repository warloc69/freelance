<?php
/**
 * File described AbstractController class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */

namespace controller;

use framework\DIContainer;

/**
 *  AbstractController class base class for all controllers
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */
class AbstractController extends DIContainer
{
    protected $request = null;
    protected $model = null;
}