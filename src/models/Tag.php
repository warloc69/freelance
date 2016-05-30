<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 23:05
 */

namespace model;

use framework\QueryBuilder;

class Tag extends QueryBuilder
{
    private $request = null;

    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "tags";
        $this->db      = $db->getConnection();
    }

    function queryColumn()
    {
        $this->select('tbl.name, tgs.project_id');
    }

    function queryJoin()
    {
        $this->join('project_tags tgs', 'inner', 'tbl.id=tgs.tag_id');
    }


}