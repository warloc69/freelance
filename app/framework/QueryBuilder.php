<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 21:49
 */

namespace framework;

class QueryBuilder extends Query
{
    private $params = array();
    protected $db = null;
    protected $limit_start = 0;
    protected $limit_offset = 5;

    function setState($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    function queryJoin()
    {
    }

    function queryHaving()
    {
    }

    function queryGroup()
    {
    }

    function queryOrder()
    {
    }

    function queryWhere($info)
    {
        foreach ($info as $criteria) {
            $this->where($criteria['field'], $criteria['operator'], $criteria['value']);
        }
    }

    function queryColumn()
    {
        $this->select('*');
    }

    function queryLimit($limit_start, $limit_offset)
    {
        $this->limit($limit_start, $limit_offset);
    }

    function getItem($info)
    {
        return $this->getResult($info);
    }

    function getList($info = [])
    {
        return $this->getResult($info, false);
    }

    function getTotal($info)
    {
        $this->resetContext();
        $this->select('count(1) as total');
        $this->queryJoin();
        $this->queryWhere($info);
        $st = $this->db->prepare($this->getQuery());
        $st->execute();
        return $st->fetch(\PDO::FETCH_ASSOC);
    }

    function getResult($info, $singl = true)
    {
        $this->queryColumn();
        $this->queryJoin();
        $this->queryWhere($info);
        $this->queryHaving();
        $this->queryGroup();
        $this->queryOrder();
        if ($singl) {
            $this->queryLimit(0, 1);
        } else {
            $this->queryLimit(0, 5);
        }
        $st = $this->db->prepare($this->getQuery());
        $st->execute();
        $result = null;
        if ($singl) {
            $result = $st->fetch(\PDO::FETCH_ASSOC);
        } else {
            $result = $st->fetchAll(\PDO::FETCH_ASSOC);
        }
        $this->resetContext();
        return $result;
    }

    function add($params)
    {
        $keys          = "";
        $prepared_keys = "";
        $values        = array();
        foreach ($params as $key => $value) {
            $keys .= $key.',';
            $prepared_keys .= ':'.$key.',';
            $values[':'.$key] = $value;
        }
        $keys          = rtrim($keys, ',');
        $prepared_keys = rtrim($prepared_keys, ',');
        $sql           = "INSERT INTO ".$this->table."(".$keys.")".
            "VALUES (".$prepared_keys.")";
        $statement     = $this->db->prepare($sql);
        $statement->execute($values);
        return $this->db->lastInsertId();
    }

    function getCriteria($field, $operator, $value)
    {
        return [
            'field'    => $field,
            'value'    => $value,
            'operator' => $operator
        ];
    }


}