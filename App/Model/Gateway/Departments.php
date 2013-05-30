<?php
namespace App\Model\Gateway;

class Departments extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("departments");
    }
}