<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 21:31
 */

namespace model;

use framework\QueryBuilder;

class User extends QueryBuilder
{
    private $request = null;

    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "users";
        $this->db      = $db->getConnection();
        @session_start();
    }


    function queryColumn()
    {
    }

    function getItem($info)
    {
        $this->select('*');
        return parent::getItem($info); // TODO: Change the autogenerated stub
    }

    function getTop()
    {
        $this->select(' tbl.first_name, tbl.last_name, avg(prj.reit) avg_reit ');
        $this->join('project prj', 'inner', ' prj.implementer = tbl.id and prj.status = \'F\'');
        $this->group('prj.implementer');
        $this->order('avg_reit desc');
        $this->limit(0, 5);
        return $this->getList([]);
    }


}