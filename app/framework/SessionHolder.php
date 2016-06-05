<?php
/**
 * File described SessionHolder class for accessing to session
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 *  SessionHolder class for accessing to session
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class SessionHolder
{
    /**
     * SessionHolder constructor.
     */
    public function __construct()
    {
        @session_start();
    }

    /**
     * return user id
     * 
     * @return user id
     */
    public function getUserId()
    {
        $user_id = null;
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        return $user_id;
    }

    /**
     * set to session user id
     * 
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $_SESSION['user_id'] = $user_id;
    }

    /**
     * return user type
     * 
     * @return user type
     */
    public function getUserType()
    {
        $user_type = null;
        if (isset($_SESSION['user_type'])) {
            $user_type = $_SESSION['user_type'];
        }
        return $user_type;
    }

    /**
     * set user type 
     * 
     * @param $user_type
     */
    public function setUserType($user_type)
    {
        $_SESSION['user_type'] = $user_type;
    }

    /**
     * add parameter to session
     * 
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if ($value != null) {
            $_SESSION[$key] = $value;
        } else {
            unset($_SESSION[$key]);
        }
    }

    /**
     * return parameter from session
     * 
     * @param $key
     *
     * @return value
     */
    public function get($key)
    {
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }
        return $value;
    }

    /**
     * clear session
     */
    public function destroy()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_type']);
    }
}