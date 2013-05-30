<?php
namespace App\Model\Gateway;

class Ideas extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("ideas");
    }
}