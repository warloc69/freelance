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
    private $project = null;

    /**
     * User constructor.
     *
     * @param $project
     * @param $request
     * @param $model
     */
    function __construct($project, $request, $model)
    {
        parent::__construct();
        $this->request = $request;
        $this->project = $project;
        $this->model   = $model;
    }

    /**
     *  make mane page with Top of freelancer
     */
    function getTop()
    {
        $criteria[] = $this->model->getCriteria('status', '=', 'N');
        if ($this->request->get('reit') != null) {
            $criteria[] = $this->model->getCriteria('expected_rait', '<', $this->request->get('reit'));
        }
        if ($this->request->get('budget') != null) {
            $criteria[] = $this->model->getCriteria('cost', '<', $this->request->get('budget'));
        }
        if ($this->request->get('tags') != null) {
            $tags             = $this->request->get('tags');
            $tags             = '(\''.str_replace(',', '\',\'', $tags).'\')';
            $projects_by_tags = $this->get('tags.model')->getList([$this->model->getCriteria('name', 'in', $tags)]);
            $ids              = "";
            foreach ($projects_by_tags as $project) {
                $ids .= $project['project_id'].',';
            }
            $ids        = rtrim($ids, ',');
            $criteria[] = $this->model->getCriteria('tbl.id', 'in', '('.$ids.')');
        }
        if ($this->request->get('deadline') != null) {
            $date       = date('Y-m-d', strtotime(str_replace('.', '/', $this->request->get('deadline'))));
            $criteria[] = $this->model->getCriteria('dedline', '<', $date);
        }

        if ($this->request->get('page') != null) {
            $this->project->limit($this->request->get('page') * 10, 10);
        } else {
            $this->project->limit(0, 10);
        }
        $pr = $this->project->getList($criteria);
        foreach ($pr as $key => $project) {
            $tg   = $this->get('tags.model')->getList([$this->model->getCriteria('project_id', '=', $project['id'])]);
            $tags = "";
            foreach ($tg as $tag) {
                $tags .= ' '.$tag['name'].',';
            }
            $pr[$key]["tags"] = rtrim($tags, ',');
        }
        $pr['top']   = $this->model->getTop();
        $pr['total'] = $this->project->getTotal($criteria)['total'];
        $this->get("user.view")->display($pr);
    }


    /**
     * logout user from system
     */
    function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_type']);
        header('Location: /');
    }
    
    /**
     * authorize or register user 
     */
    function auth()
    {
        if ($this->request->getDirtyValue('code') != null) {

            $params = array(
                'client_id'     => ConfigHolder::getConfig('google_client_id'),
                'client_secret' => ConfigHolder::getConfig('google_secret'),
                'redirect_uri'  => ConfigHolder::getConfig('google_redirect'),
                'grant_type'    => 'authorization_code',
                'code'          => $this->request->getDirtyValue('code')
            );

            $url  = 'https://accounts.google.com/o/oauth2/token';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);
            $tokenInfo = json_decode($result, true);
        }
        if (isset($tokenInfo['access_token'])) {
            $params['access_token'] = $tokenInfo['access_token'];

            $userInfo = json_decode(
                file_get_contents(
                    'https://www.googleapis.com/oauth2/v1/userinfo?'
                    .urldecode(http_build_query($params))
                ),
                true
            );
        }

        $_SESSION['user'] = $userInfo;

        $user     = [
            "first_name" => explode(" ", $userInfo['name'])[0],
            "last_name"  => explode(" ", $userInfo['name'])[1],
            "email"      => $userInfo['email']
        ];
        $creteria = [
            $this->model->getCriteria('email', '=', $userInfo['email'])
        ];
        $item     = $this->model->getItem($creteria);
        if ($item == false) {
            $_SESSION['user_id']   = $this->model->add($user);
            $_SESSION['user_type'] = 'PM';
        } else {
            $_SESSION['user_id']   = $item['id'];
            $_SESSION['user_type'] = $item['type'] != null?$item['type']:'PM';
        }
        header('Location: /');
    }

    /**
     *  Change user role
     */
    function changeRole() {
        $_SESSION['user_type'] = $this->request->get('user-role');
        header('Location: /project');
    }
}