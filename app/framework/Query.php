<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 22:08
 */

namespace framework;

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

    function join($name, $type, $value)
    {
        $this->join .= $type.' join ';
        $this->join .= $name.' on '.$value.' ';
    }

    function order($name)
    {
        if (empty($this->order)) {
            $this->order = 'ORDER BY ';
        } else {
            $this->order .= ',';
        }
        $this->order .= $name.' ';
    }

    function group($field)
    {
        $this->group .= 'GROUP BY '.$field.' ';
    }

    function having($field, $operation, $function)
    {
        $this->having .= $function.' '.$operation.' '.$field;
        $this->select .= $function.' res_having ';
    }

    function limit($start, $offset)
    {
        if (empty($this->limit)) {
            $this->limit .= 'LIMIT '.$start.','.$offset.' ';
        }
    }

    function select($name)
    {
        if (empty($this->select)) {
            $this->select = 'select ';
        }
        $this->select .= $name.' ';
    }

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