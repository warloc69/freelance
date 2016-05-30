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
    private $request = null;

    function __construct($request)
    {
        parent::__construct();
        $this->request = $request;
        $this->table   = "bids";
    }

}