<?php
/**
 * Created by PhpStorm.
 * User: warloc
 * Date: 29.05.2016
 * Time: 12:59
 */

namespace controller;


use framework\DIContainer;

class AbstractController extends DIContainer
{
    protected $request = null;
    protected $model =  null;
}