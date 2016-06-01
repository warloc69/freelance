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
class Project
{
    /**
     * render html context
     * @param $context information for rendering
     */
    function display($context , $islist = false)
    {
        include "tmp/Header.php";
        if($islist) {
            $this->showList($context);
        } else {
            $this->generateBody($context);
        }
        include "tmp/Footer.php";
    }
    
    /**
     * render project list 
     * @param $context information for rendering
     */
    function showList($context) {
        include "tmp/ProjectList.php";
    }

    /**
     * render body html context
     * @param $context information for rendering
     */
    function generateBody($context)
    {
        $url = 'https://accounts.google.com/o/oauth2/auth';

        $params = array(
            'redirect_uri'  => ConfigHolder::getConfig('google_redirect'),
            'response_type' => 'code',
            'client_id'     => ConfigHolder::getConfig('google_client_id'),
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );
        $link   = $url.'?'.urldecode(http_build_query($params));
        
            include "tmp/ProjectBodyFreelancer.php";
    }
}