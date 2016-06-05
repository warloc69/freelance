<?php
/**
 * File described LoginManager controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */

namespace controller;

use framework\ConfigHolder;

/**
 *  LoginManager controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */
class LoginManager extends AbstractController
{
    /**
     * LoginManager constructor.
     *
     * @param $session
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * logout user from system
     */
    public function logout()
    {
        $this->session->destroy();
        $this->get('response')->redirectToMainPage();
    }

    /**
     * redirect to login google page
     */
    public function login() {
        $url = $this->get('request')->get('project_url');
        $this->session->set('project_url', $url);
        $this->get('response')->redirect(ConfigHolder::getConfig('google_autorize'));
    }
    
    /**
     * authorize or register user
     */
    public function auth()
    {
        if ($this->get('request')->getDirtyValue('code') != null) {

            $params = array(
                'client_id'     => ConfigHolder::getConfig('google_client_id'),
                'client_secret' => ConfigHolder::getConfig('google_secret'),
                'redirect_uri'  => ConfigHolder::getConfig('google_redirect'),
                'grant_type'    => 'authorization_code',
                'code'          => $this->get('request')->getDirtyValue('code')
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

        $user     = [
            "first_name" => explode(" ", $userInfo['name'])[0],
            "last_name"  => explode(" ", $userInfo['name'])[1],
            "email"      => $userInfo['email']
        ];
        $creteria = [
            $this->get('user.model')->getCriteria('email', '=', $userInfo['email'])
        ];
        $item     = $this->get('user.model')->getItem($creteria);
        if ($item == false) {
            $this->session->setUserId($this->model->add($user));
            $this->session->setUserType('PM');
        } else {
            $this->session->setUserId($item['id']);
            $user_type = $item['type'] != null?$item['type']:'PM';
            $this->session->setUserType($user_type);
        }
        $url = $this->session->get('project_url');
        if(!empty($url)) {
            $this->session->set('project_url', null);
            $this->get("response")->redirect($url);
        } else {
            $this->session->set('project_url', null);
            $this->get("response")->redirect("/project");
        }
    }
}