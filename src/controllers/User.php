<?php
/**
 * File described User controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */

namespace controller;

use framework\ConfigHolder;

/**
 *  User controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */
class User extends AbstractController
{
    /**
     * User constructor.
     *
     * @param $project
     * @param $request
     * @param $model
     */
    public function __construct($request, $model)
    {
        parent::__construct();
        $this->request = $request;
        $this->model   = $model;        
    }

    /**
     *  Change user role
     */
    public function changeRole() {
        $this->session->setUserType($this->request->get('user-role'));
        $this->get("response")->redirect('/project');
    }

    /**
     * generate Top 5 users
     * 
     * @param $context
     *
     * @return mixed
     */
    public function getTop(&$context) {
        $md = $this->model->getTop();
        $context["top_users"] = $md;
        return $this->get("user.view")->generateBody($md);
    }
}