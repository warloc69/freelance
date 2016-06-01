<?php
/**
 * File described User model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */

namespace model;

use framework\QueryBuilder;

/**
 *  User model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */
class User extends QueryBuilder
{
    private $request = null;

    /**
     * User constructor.
     *
     * @param $request
     * @param $db
     */
    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "users";
        $this->db      = $db->getConnection();
        @session_start();
    }

    /**
     * function used for build select section from part
     */
    function queryColumn()
    {
    }

    /**
     * function return item from database
     *
     * @info array created accordingly to getCriteria method
     */
    function getItem($info)
    {
        $this->select('*');
        return parent::getItem($info); 
    }

    /**
     * function return Top users from database
     */
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