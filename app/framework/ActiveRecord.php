<?php
namespace framework;

abstract class  ActiveRecord
{
    protected $db;
    public abstract function getPrimaryIdName();
    public abstract function getTableName();	
    private function getColumns($props, $forupdate= false) {
        $result = ' ';
        foreach ($props as $prop) {
            if ($prop->getValue($this) != NULL) {
                $result .=  ($forupdate ? $prop->getName().'=' : ''). ':'.$prop->getName() .', ';
            }
        }
        $result = substr($result, 0, strlen($result)-2);
        return $result;
    }
	/**
     *  pars array with information to class public Properties
	 *  $data  -map must contains value of properies. Example array( 'id' => 1)
     */
    public function fillProperty($data) {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($props as $prop) {
            if (isset($data[$prop->getName()])) {
                $prop->setValue($this,$data[$prop->getName()]);
            }
        }
    }
	/**
     *  return Class with information from DB in Properties
	 *  $data  - map must contains value of primary key. Example array( 'id' => 1)
     */
    public function find($data) {
        if(empty($data[$this->getPrimaryIdName()]) || $this->db == NULL) throw new \Exception("db works incorrect");
        $reflect = new \ReflectionClass($this);
        $statement = $this->db->prepare("SELECT * FROM ".$this->getTableName()." where "
            .$this->getPrimaryIdName()."=:".$this->getPrimaryIdName());
        $statement->execute (array(':'.$this->getPrimaryIdName() => $data[$this->getPrimaryIdName()]));
        return $statement->fetchALL(\PDO::FETCH_CLASS, $reflect->getName(),array($this->db));
    }
	/**
     *  delete row from DB
	 *  $data  - map must contains value of primary key. Example array( 'id' => 1)
     */
    public  function remove($data) {
        if(empty($data[$this->getPrimaryIdName()]) || $this->db == NULL) throw new \Exception("db works incorrect");
        $sql = "delete from ".$this->getTableName()." where "
            .$this->getPrimaryIdName()."=:".$this->getPrimaryIdName();
        $statement = $this->db->prepare($sql);
        $statement->execute (array(':'.$this->getPrimaryIdName() => $data[$this->getPrimaryIdName()]));
    }
    /**
     *  return array of Classes with information from DB in Properties
	 * 
     */
    public function getAllAsClass(){
        if(empty($data[$this->getTableName()]) || $this->db == NULL) return null;
        $st = $this->db->prepare("SELECT * FROM ".$this->getTableName());
        $st->execute();
        $reflect = new \ReflectionClass($this);
        return $st->fetchALL(\PDO::FETCH_CLASS, $reflect->getName(),array($this->db));
    }
	 /**
     *  return array with information from DB
	 *  
     */
    public function getAll(){
        if($this->db == NULL) throw new \Exception("db works incorrect");
        $st = $this->db->prepare("SELECT * FROM ".$this->getTableName());
        $st->execute();
        return $st->fetchALL(\PDO::FETCH_ASSOC);
    }

	/**
     *  update information in DB
	 *  $data  - map must contains value of properies. Example array( 'id' => 1, 'fname' => 'Helmut')
     */
    public function update($data) {
        if(empty($data[$this->getPrimaryIdName()])|| $this->db == NULL) throw new \Exception("db works incorrect");
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $this->fillProperty($data);
        $sql = "UPDATE ".$this->getTableName()." SET ".$this->getColumns($props,true)." WHERE "
            .$this->getPrimaryIdName()."=:".$this->getPrimaryIdName();
        $statement = $this->db->prepare($sql);
        $arr = array();
        foreach ($props as $prop) {
            if ($prop->getValue($this) != NULL) {
                $arr[ ':'.$prop->getName()] = $data[$prop->getName()];
            }
        }
        $statement->execute($arr);
    }
	/**
     *  create information in DB
	 *  $data  - map must contains value of properies. Example array( 'fname' => 'Helmut','age' => '10')
     */
    public function create($data) {
        if($this->db == NULL) throw new \Exception("db works incorrect");
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $this->fillProperty($data);
        $sql = "INSERT INTO ".$this->getTableName()."(".str_replace(':','' ,$this->getColumns($props)).")".
            "VALUES (".$this->getColumns($props).")";
        $statement = $this->db->prepare($sql);
        $arr = array();
        foreach ($props as $prop) {
            if ($prop->getValue($this) != NULL) {
                $arr[ ':'.$prop->getName()] = $data[$prop->getName()];
            }
        }
        $statement->execute($arr);
        $props[$this->getPrimaryIdName()] = $this->db->lastInsertId();
    }
	/**
     *  save  class public properies into DB
     */
    public function save() {
        if(empty($data[$this->getPrimaryIdName()])|| $this->db == NULL) throw new \Exception("db works incorrect");
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $data = array();
        foreach ($props as $prop) {
            if ($prop->getValue($this) != NULL) {
                $data[$prop->getName()] =  $prop->getValue($this);
            }
        }
        if(isset($data[$this->getPrimaryIdName()])) {
            $this->update($data);
        } else {
            $this->create($data);
        }
    }
}