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
    public function generateBody($context)
    {
        switch ($context["type"]) {
            case "MPL" : {
                include "tmp/MyProjectList.php";
                break;
            } 
            case "PL": {
                include "tmp/ProjectList.php";
                $this->assignValue('@top',$context['top']);
                break;
            }
            case "P": {
                include "tmp/ProjectBody.php";
            }
                
        }
    }
}