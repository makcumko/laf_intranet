<?php
namespace App\Model\Gateway;

class Images extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("Images");
    }
}