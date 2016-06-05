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
    /**
     * Tag constructor.
     *
     * @param $request
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table = "tags";
    }

    /**
     * function used for build select section from part
     */
    protected function queryColumn()
    {
        $this->select('tbl.id, tbl.name, tgs.project_id');
    }

    /**
     * function used for build join section from part
     */
    protected function queryJoin()
    {
        $this->join('project_tags tgs', 'inner', 'tbl.id=tgs.tag_id');
    }

    /**
     * @param $params add new project tags
     */
    public function addProjectTag($params)
    {
        $this->table = 'project_tags';
        $this->add($params);
        $this->table = 'tags';
    }


}