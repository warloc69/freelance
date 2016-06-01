<?php
/**
 * File described QueryBuilder class
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * QueryBuilder class using for execute query
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class QueryBuilder extends Query
{
    protected $db = null;
    protected $limit_start = 0;
    protected $limit_offset = 5;


    /**
     * function used for build join section from part
     */
    function queryJoin()
    {
    }

    /**
     * function used for build having section from part
     */
    function queryHaving()
    {
    }

    /**
     * function used for build group section from part
     */
    function queryGroup()
    {
    }

    /**
     * function used for build order section from part
     */
    function queryOrder()
    {
    }

    /**
     * function used for build where section from part
     */
    function queryWhere($info)
    {
        foreach ($info as $criteria) {
            $this->where($criteria['field'], $criteria['operator'], $criteria['value']);
        }
    }

    /**
     * function used for build select section from part
     */
    function queryColumn()
    {
        $this->select('*');
    }

    /**
     * function used for build limit section
     */
    function queryLimit($limit_start, $limit_offset)
    {
        $this->limit($limit_start, $limit_offset);
    }

    /**
     * function return item from database
     * 
     * @info array created accordingly to getCriteria method
     */
    function getItem($info)
    {
        return $this->getResult($info);
    }

    /**
     * function return List from database
     *
     * @info array created accordingly to getCriteria method , can be ignored, default empty
     */
    function getList($info = [])
    {
        return $this->getResult($info, false);
    }

    /**
     * function return total from database
     *
     * @info array created accordingly to getCriteria method
     */
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

    /**
     * function return result of select from database
     *
     * @param info array created accordingly to getCriteria method
     * @param singl if true return item else list, default true
     */
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

    /**
     * function insert item to database
     * @param $params array key => value
     *
     * @return id of new item
     */
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

    /**
     * function update item in database
     * @param $params array key => value
     * @param $where array key => value
     *
     */
    function update($params, $where)
    {
        $keys      = "";
        $values    = array();
        $where_key = "";
        foreach ($params as $key => $value) {
            $keys .= $key.'=:'.$key.',';
            $values[':'.$key] = $value;
        }
        foreach ($where as $key => $value) {
            $where_key .= $key.'=:'.$key.' AND ';
            $values[':'.$key] = $value;
        }
        $keys      = rtrim($keys, ',');
        $where_key = rtrim($where_key, 'AND ');
        $sql       = "UPDATE ".$this->table." SET ".$keys." WHERE ".$where_key;
        $statement = $this->db->prepare($sql);
        $statement->execute($values);
        return true;
    }

    /**
     * function make criteria for select 
     * @param $field
     * @param $operator
     * @param $value
     *
     * @return array
     */
    function getCriteria($field, $operator, $value)
    {
        return [
            'field'    => $field,
            'value'    => $value,
            'operator' => $operator
        ];
    }


}