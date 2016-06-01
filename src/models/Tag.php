<?php
/**
 * File described Tag model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */

namespace model;

use framework\QueryBuilder;

/**
 *  Tag model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */
class Tag extends QueryBuilder
{
    private $request = null;

    /**
     * Tag constructor.
     *
     * @param $request
     * @param $db
     */
    function __construct($request, $db)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "tags";
        $this->db      = $db->getConnection();
    }

    /**
     * function used for build select section from part
     */
    function queryColumn()
    {
        $this->select('tbl.id, tbl.name, tgs.project_id');
    }

    /**
     * function used for build join section from part
     */
    function queryJoin()
    {
        $this->join('project_tags tgs', 'inner', 'tbl.id=tgs.tag_id');
    }

    /**
     * @param $params add new project tags
     */
    function addProjectTag($params) {
        $this->table = 'project_tags';
        $this->add($params);
        $this->table = 'tags';
    }


}