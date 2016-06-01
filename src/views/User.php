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

use framework\ConfigHolder;

/**
 *  User view class
 *
 * PHP version 5
 *
 * @namespace  view
 * @author     sivanchenko@mindk.com
 */
class User
{
    /**
     * render html context
     * @param $context information for rendering
     */
    function display($context)
    {
        include "tmp/Header.php";
        $this->generateBody($context);
        include "tmp/Footer.php";
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
        include "tmp/UserBody.php";
    }
}