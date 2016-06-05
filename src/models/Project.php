<?php
/**
 * File described Project model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */

namespace model;

use framework\QueryBuilder;

/**
 *  Project model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */
class Project extends QueryBuilder
{
    /**
     * Project constructor.
     *
     * @param $request
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table   = "project";
    }

    protected function queryOrder()
    {
        $this->order("created_at desc");
    }
}