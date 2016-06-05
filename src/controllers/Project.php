<?php
/**
 * File described Project controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */

namespace controller;

use framework\ConfigHolder;
use framework\SmtpMailer;

/**
 *  Project controller class
 *
 * PHP version 5
 *
 * @namespace  controller
 * @author     sivanchenko@mindk.com
 */
class Project extends AbstractController
{
    /**
     * Project constructor.
     *
     * @param $request
     * @param $model
     */
    public function __construct($request, $model, $user, $session)
    {
        $this->request = $request;
        $this->model   = $model;
        $this->user    = $user;
        $this->session = $session;
    }

    /**
     * return project
     */
    public function getItem()
    {
        $criteria = [$this->model->getCriteria('id', '=', $this->request->get('project.id'))];
        $pr       = $this->model->getItem($criteria);
        $tg       = $this->get('tags.model')->getList([$this->model->getCriteria('project_id', '=', $pr['id'])]);
        $tags     = "";
        foreach ($tg as $tag) {
            $tags .= ' '.$tag['name'].',';
        }
        $pr["tags"] = rtrim($tags, ',');
        $pr["type"] = "P";
        $pr["user_type"] = $this->session->getUserType();
        $pr["user_id"] = $this->session->getUserId();
        $this->get("project.view")->display($pr);
    }

    /**
     * Create new bid form freelancer
     * and sent email to project owner
     */
    public function addBid()
    {
        $id       = $this->request->get('project.id');
        $criteria = [$this->model->getCriteria('id', '=', $id)];
        $project  = $this->model->getItem($criteria);
        $bid_id   = uniqid();
        $user     = $this->get('user.model')->getItem([$this->model->getCriteria('id', '=', $project['owner'])]);
        $comment  = $this->request->get('comment');             
        $result = $this->get('mailer')->sendNewBid($user['first_name'],$user['email'],$id,$bid_id,$comment);
        if ($result) {
            $bid_info = [
                "user_id"    => $this->session->getUserId(),
                "project_id" => $id,
                "token"      => $bid_id
            ];
            $this->get('bid.model')->add($bid_info);
        }
        die($result);
    }

    /**
     * Make list with project
     */
    public function getList()
    {
        if(empty($this->session->getUserId())) {
            $this->get("response")->redirect(ConfigHolder::getConfig('google_autorize'));
        }
        $this->model->resetContext();
        $criteria = [];
        if ($this->session->getUserType() == 'PM') {
            $criteria[] = $this->model->getCriteria('owner', '=', $this->session->getUserId());
        } else {
            $criteria[] = $this->model->getCriteria('implementer', '=', $this->session->getUserId());
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
        $pr["type"] = "MPL";
        $pr["user_type"] = $this->session->getUserType();
        $pr["user_id"] = $this->session->getUserId();
        $this->get("project.view")->display($pr);
    }

    /**
     * create new project
     */
    public function add()
    {
        $project_info = [
            "name"          => $this->request->get('name'),
            "description"   => $this->request->get('description'),
            "dedline"       => date('Y-m-d', strtotime(str_replace('.', '/', $this->request->get('deadline')))),
            "cost"          => $this->request->get('budget'),
            "owner"         => $this->session->getUserId(),
            "expected_rait" => $this->request->get('reit'),
            "status"        => 'N',
            "created_at"    => date('Y-m-d')
        ];
        $project_id   = $this->model->add($project_info);
        $tags         = explode(',', $this->request->get('tags'));
        foreach ($tags as $tag) {
            $tag_criteria = [
                $this->model->getCriteria('name', '=', $tag)
            ];
            $tagInfo      = $this->get('tags.model')->getItem($tag_criteria);
            if (!$tagInfo) {
                $tag_id = $this->get('tags.model')->add(["name" => $tag]);
            } else {
                $tag_id = $tagInfo['id'];
            }
            $project_tags_info = [
                "project_id" => $project_id,
                "tag_id"     => $tag_id
            ];
            $this->get('tags.model')->addProjectTag($project_tags_info);
        }
    }

    /**
     * change status to Finished for project
     */
    public function statusChange()
    {
        $id           = $this->request->get('project.id');
        $reit         = $this->request->get('reit.id');
        $project_info = [
            "reit"   => $reit,
            "status" => 'F'
        ];
        $this->model->update($project_info, ['id' => $id]);
    }

    /**
     * Change status to Active for project and set implementer for project
     */
    public function updateBid()
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

        $implementer_criteria = [
            $this->model->getCriteria('id', '=', $bid['user_id']),
        ];

        $implementer = $this->get('user.model')->getItem($implementer_criteria);        
       
        $result = $this->get('mailer')->sendBidAccept($implementer['first_name'],$implementer['email'],$id);

        $this->get("response")->redirectToMainPage();
    }
    
    /**
     *  make mane page with Top of freelancer
     */
    public function getMainPage()
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
            $this->model->limit($this->request->get('page') * 10, 10);
        } else {
            $this->model->limit(0, 10);
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
        $pr['top']   = $this->get('user')->getTop($pr);
        $pr['total'] = $this->model->getTotal($criteria)['total'];
        $pr["type"] = "PL";
        $pr["user_type"] = $this->session->getUserType();
        $pr["user_id"] = $this->session->getUserId();
        $this->get("project.view")->display($pr);
    }
}