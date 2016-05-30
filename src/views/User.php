<?php
namespace view;

/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 21:32
 */
use framework\ConfigHolder;

class User
{
    function display($context)
    {
        include "tmp/Header.php";
        $this->generateBody($context);
        include "tmp/Footer.php";
    }

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