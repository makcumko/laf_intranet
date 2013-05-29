<?php
namespace App\Model\Gateway;

use App\Model\Registry;

class Users extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("users");
    }

    function read($id) {
        $user = parent::read($id);
        if (empty($user)) {
            $user = [
                'id' => 0,
                'name' => "Anon"
            ];
        }

//        $user['balance'] = Registry::Singleton("\App\Model\Service\Ultima")->getUserBalance($user['user_id'], $user['ka_id']);

        return $user;
    }
}