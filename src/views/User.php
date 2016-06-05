<?php
/**
 * File described User view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */

namespace view;

/**
 *  User view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */
class User extends AbstractView
{
    /**
     * render body html context
     * @param $context information for rendering
     */
    function generateBody($context)
    {
        include "tmp/UserBody.php";
    }
}