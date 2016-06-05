<?php
/**
 * File described Response class for redirect
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 *  Response class for redirect
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class Response
{
    /**
     * redirect to url
     * @param $href url
     */
    public function redirect($href) {
        header('Location: '.$href);
    }

    /**
     * redirect to main page
     */
    public function redirectToMainPage(){
        header('Location: /');
    }
}