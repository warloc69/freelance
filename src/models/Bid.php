<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 28.05.2016
 * Time: 23:05
 */

namespace model;

use framework\QueryBuilder;

class Bid extends QueryBuilder
{
    function __construct($db)
    {
        parent::__construct();
        $this->db = $db->getConnection();
        $this->table   = "bids";
    }

}