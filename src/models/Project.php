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
    private $request = null;

    /**
     * Project constructor.
     *
     * @param $request
     * @param $db
     */
    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "project";
        $this->db      = $db->getConnection();
        @session_start();
    }
}