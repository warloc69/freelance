<?php
/**
 * File described DefaultController class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */

namespace controller;

/**
 *  DefaultController class used if correct controller not found
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */
class DefaultController extends AbstractController
{
    /**
     * used if correct action not found
     */
    public function _actionDefault()
    {
        $this->get('default.view')->display();
    }
    
    /**
     * used if correct action not found
     */
    public function serverError()
    {
        $this->get('default.view')->display("500 Internal Server error");
    }
}