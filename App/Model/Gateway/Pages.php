<?php
namespace App\Model\Gateway;

class Pages extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("pages");
    }
}