<?php
namespace App\Model\Gateway;

class Comments extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("comments");
    }
}