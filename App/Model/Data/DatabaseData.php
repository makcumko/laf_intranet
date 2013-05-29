<?php
namespace App\Model\Data;

class DatabaseData extends \App\Model\Data\AbstractData
{
    protected $source = '';

    function __construct($config, $load = false)
    {
        $this->source = 'Database/'.$config;
        parent::__construct($load);
//        $this->loadContainer();

    }

}