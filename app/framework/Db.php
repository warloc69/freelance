<?php
/**
 * File described class accessing to for accessing to db
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 * ConfigHolder class for accessing to db
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */
class Db
{
    private $connection = null;

    /**
     * Db constructor.
     * used configuration from ConfigHolder
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

    /**
     * Function returns connection to DB
     */
    public function getConnection()
    {
        return $this->connection;
    }
}