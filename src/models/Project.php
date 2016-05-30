<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 20:50
 */

namespace model;

use framework\QueryBuilder;

class Project extends QueryBuilder
{
    private $request = null;

    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "project";
        $this->db      = $db->getConnection();
        session_start();
    }

    function addTags()
    {
    }
}