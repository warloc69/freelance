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

    /**
     * User constructor.
     *
     * @param $request
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table   = "users";
        
    }

    /**
     * function used for build select section from part
     */
    protected function queryColumn()
    {
    }

    /**
     * function return item from database
     *
     * @info array created accordingly to getCriteria method
     */
    public function getItem($info)
    {
        $this->select('*');
        return parent::getItem($info); 
    }

    /**
     * function return Top users from database
     */
    public function getTop()
    {
        $this->select(' tbl.first_name, tbl.last_name, avg(prj.reit) avg_reit ');
        $this->join('project prj', 'inner', ' prj.implementer = tbl.id and prj.status = \'F\'');
        $this->group('prj.implementer');
        $this->order('avg_reit desc');
        $this->limit(0, 5);
        return $this->getList([]);
    }


}