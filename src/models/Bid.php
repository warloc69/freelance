<?php
/**
 * File described Bid model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */

namespace model;

use framework\QueryBuilder;

/**
 *  Bid model class
 *
 * PHP version 5
 *
 * @namespace  model
 * @author     sivanchenko@mindk.com
 */
class Bid extends QueryBuilder
{
    /**
     * Bid constructor.
     *
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db);
        $this->table   = "bids";
    }

}