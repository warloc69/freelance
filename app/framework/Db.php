<?php

namespace framework;

class Db
{
    private $connection = null;

    /**
     *returns connection to DB
     * $db_server - connection string. example = "mysql:host=localhost;dbname=students"
     * $db_user - user name
     * $db_pass - password
     */
    public function __construct()
    {
        if ($this->connection == null) {
            try{
                $this->connection = new \PDO(
                    ConfigHolder::getConfig('connection_string'),
                    ConfigHolder::getConfig('user'),
                    ConfigHolder::getConfig('pass')
                );
            } catch(\PDOException $e){
                $this->connection = null;
                error_log($e->getMessage());
            }
        }
    }

    function getConnection()
    {
        return $this->connection;
    }
}