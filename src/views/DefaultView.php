<?php
/**
 * File described Default view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */

namespace view;

/**
 *  Default view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */
class DefaultView
{
    /**
     * render html
     */
    public function display($code = "404. Page not found")
    {        
        include "tmp/error.php";
    }

}