<?php
/**
 * File described Project view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */

namespace view;

use framework\ConfigHolder;

/**
 *  Project view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */
class Project extends AbstractView
{

    /**
     * render body html context
     * @param $context information for rendering
     */
    function generateBody($context)
    {
        if(isset($context["isList"])) {
            include "tmp/ProjectList.php";
        } else {
            include "tmp/ProjectBodyFreelancer.php";
        }

    }
}