<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 20:55
 */

namespace view;
use framework\ConfigHolder;

class Project
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

     //   $_SESSION['user_type'] = 'PM';
      //  if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'F') {
            include "tmp/ProjectBodyFreelancer.php";
     //   } 
    }
}