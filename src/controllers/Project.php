<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 20:49
 */

namespace controller;


class Project extends AbstractController
{
    function __construct($request, $model)
    {
        $this->request = $request;
        $this->model = $model;
    }

    function getItem() {
        $criteria = [$this->model->getCriteria('id','=',$this->request->get('project.id'))];
        $pr = $this->model->getItem($criteria);
        $tg =  $this->get('tags.model')->getList([$this->model->getCriteria('project_id','=',$pr['id'])]);
        $tags = "";
        foreach ($tg as $tag) {
            $tags .= ' '.$tag['name'] . ',';
        }
        $pr["tags"] = rtrim($tags,',');
        $this->get("project.view")->display($pr);
    }

    function getList() {
        echo "constr : Project , action : getList".$this->request->get('project.id');
    }
    function add() {
        echo "constr : Project , action : add".$this->request->get('project.id');
    }
    function statusChange() {
        echo "constr : Project , action : statusChange".$this->request->get('project.id');
    }
}