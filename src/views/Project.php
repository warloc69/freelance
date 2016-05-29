<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 20:55
 */

namespace view;


class Project
{
    function display($context) {
        include "tmp/Header.php";
        $this->generateBody($context);
        include "tmp/Footer.php";
    }

    function generateBody($context) {
        $_SESSION['user_type'] = 'PM';
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'F') {
            include "tmp/ProjectBodyFreelancer.php";
        } else if(isset($_SESSION['user_type'])) {
            include "tmp/ProjectBodyCustomer.php"; 
        }
    }
}