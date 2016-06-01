<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 20:49
 */

namespace controller;

use framework\ConfigHolder;
use framework\SmtpMailer;

class Project extends AbstractController
{
    function __construct($request, $model)
    {
        $this->request = $request;
        $this->model   = $model;
    }

    function getItem()
    {
        $criteria = [$this->model->getCriteria('id', '=', $this->request->get('project.id'))];
        $pr       = $this->model->getItem($criteria);
        $tg       = $this->get('tags.model')->getList([$this->model->getCriteria('project_id', '=', $pr['id'])]);
        $tags     = "";
        foreach ($tg as $tag) {
            $tags .= ' '.$tag['name'].',';
        }
        $pr["tags"] = rtrim($tags, ',');
        $this->get("project.view")->display($pr);
    }

    function addBid()
    {
        $id       = $this->request->get('project.id');
        $criteria = [$this->model->getCriteria('id', '=', $id)];
        $project  = $this->model->getItem($criteria);
        $bid_id   = uniqid();
        $user     = $this->get('user.model')->getItem([$this->model->getCriteria('id', '=', $project['owner'])]);
        $comment  = $this->request->get('comment');
        $mailer   = new SmtpMailer(
            ConfigHolder::getConfig('smtp_username'),
            ConfigHolder::getConfig('smtp_password'),
            ConfigHolder::getConfig('smtp_host'),
            ConfigHolder::getConfig('smtp_port')
        );

        $user_name = $user['first_name'];
        $email     = $user['email'];
        $text      = ' Hello '.$user_name.'! You took new bid. For accepting proposition, go to the link: 
        <a href="'.ConfigHolder::getConfig('host').'/project/'.$id.'/bid/'.$bid_id.'">accept bid</a>
        <br>
        <br>
        Comment from freelancer: '.$comment;
        $headers   = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: Admin <freelance@gmail.com>\r\n";
        $result = $mailer->send($email, 'New bid from freelancer', $text, $headers);
        if ($result) {
            $bid_info = [
                "user_id"    => $_SESSION['user_id'],
                "project_id" => $id,
                "token"      => $bid_id
            ];
            $this->get('bid.model')->add($bid_info);
        }
        die($result);
    }

    function getList()
    {
        $this->model->resetContext();
        $criteria = [];
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'PM') {
            $criteria[] = $this->model->getCriteria('owner', '=', $_SESSION['user_id']);
        } else {
            $criteria[] = $this->model->getCriteria('implementer', '=', $_SESSION['user_id']);
        }
        $pr = $this->model->getList($criteria);
        foreach ($pr as $key => $project) {
            $tg   = $this->get('tags.model')->getList([$this->model->getCriteria('project_id', '=', $project['id'])]);
            $tags = "";
            foreach ($tg as $tag) {
                $tags .= ' '.$tag['name'].',';
            }
            $pr[$key]["tags"] = rtrim($tags, ',');
        }
        $this->get("project.view")->display($pr, true);
    }

    function add()
    {
        $project_info = [
            "name"          => $this->request->get('name'),
            "description"   => $this->request->get('description'),
            "dedline"       => date('Y-m-d', strtotime(str_replace('.', '/', $this->request->get('deadline')))),
            "cost"          => $this->request->get('budget'),
            "owner"         => $_SESSION['user_id'],
            "expected_rait" => $this->request->get('reit'),
            "status"        => 'N'
        ];
        $project_id   = $this->model->add($project_info);
    }

    function statusChange()
    {
        $id           = $this->request->get('project.id');
        $reit         = $this->request->get('reit.id');
        $project_info = [
            "reit"   => $reit,
            "status" => 'F'
        ];
        $this->model->update($project_info, ['id' => $id]);
    }

    function updateBid()
    {
        $id    = $this->request->get('project.id');
        $token = $this->request->get('bid.id');

        $bid_criteria = [
            $this->model->getCriteria('project_id', '=', $id),
            $this->model->getCriteria('token', '=', $token)
        ];

        $bid = $this->get('bid.model')->getItem($bid_criteria);

        $project_info = [
            "implementer" => $bid['user_id'],
            "status"      => 'A'
        ];

        $this->model->update($project_info, ['id' => $id]);
    }
}