<?php
namespace App\Model\Gateway;

class News extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("news");
    }
}