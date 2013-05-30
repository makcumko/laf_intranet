<?php
namespace App\Model\Gateway;

class IdeaVotes extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("idea_votes");
    }
}