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
    public function __construct()
    {
        $this->setHeaderFileName(null);
        $this->setFooterFileName(null);
    }

    /**
     * render body html context
     * @param $context information for rendering
     */
    public function generateBody($context)
    {
        return file_get_contents("..\\src\\views\\tmp\\UserTop.php");
    }
}