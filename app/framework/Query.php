<?php
/**
 * File described Query class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * Query class represent query to database
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class Query extends DIContainer
{
    protected $query = "";
    protected $join = "";
    protected $where = "";
    protected $from = "";
    protected $limit = "";
    protected $select = "";
    protected $order = "";
    protected $having = "";
    protected $group = "";
    protected $table = "";

    /**
     *  function build where part of query
     */
    function where($name, $operation, $value)
    {
        if (empty($this->where)) {
            $this->where .= 'where ';
        } else {
            $this->where .= 'and ';
        }
        if ($operation != 'in') {
            $this->where .= $name.' '.$operation.' \''.$value.'\' ';
        } else {
            $this->where .= $name.' '.$operation.' '.$value.' ';
        }
    }

    /**
     *  function build join part of query
     */
    function join($name, $type, $value)
    {
        $this->join .= $type.' join ';
        $this->join .= $name.' on '.$value.' ';
    }

    /**
     *  function build order part of query
     */
    function order($name)
    {
        if (empty($this->order)) {
            $this->order = 'ORDER BY ';
        } else {
            $this->order .= ',';
        }
        $this->order .= $name.' ';
    }

    /**
     *  function build group part of query
     */
    function group($field)
    {
        $this->group .= 'GROUP BY '.$field.' ';
    }

    /**
     *  function build having part of query
     */
    function having($field, $operation, $function)
    {
        $this->having .= $function.' '.$operation.' '.$field;
        $this->select .= $function.' res_having ';
    }

    /**
     *  function build limit part of query
     */
    function limit($start, $offset)
    {
        if (empty($this->limit)) {
            $this->limit .= 'LIMIT '.$start.','.$offset.' ';
        }
    }

    /**
     *  function build select part of query
     */
    function select($name)
    {
        if (empty($this->select)) {
            $this->select = 'select ';
        }
        $this->select .= $name.' ';
    }

    /**
     *  function build full query
     */
    function getQuery()
    {
        $this->query
            = $this->select.'from '.$this->table.' tbl '.
            $this->join.
            $this->where.
            $this->group.
            $this->having.
            $this->order.
            $this->limit;
        return $this->query;
    }

    /**
     *  function reset query
     */
    function resetContext()
    {
        $this->query  = '';
        $this->select = '';
        $this->join   = '';
        $this->where  = '';
        $this->group  = '';
        $this->having = '';
        $this->order  = '';
        $this->limit  = '';
    }
}