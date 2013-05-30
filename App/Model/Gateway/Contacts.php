<?php
namespace App\Model\Gateway;

class Contacts extends \App\Model\DB\AbstractDBTable {
    function __construct() {
        parent::__construct("contacts");
    }

    function getByUser($userId) {
        $contacts = $this->filter(['user_id' => $userId]);

        $result = [];
        foreach ($contacts as $row) {
            $result[$row['type']][] = $row;
        }

        return $result;
    }

    function updateForUser($userId, $data) {
        $sql = "UPDATE contacts SET flag_deleted = 1 WHERE user_id = :0:";
        $this->db->query($sql, $userId);

        foreach ($data as $type => $pairs) {
            foreach ($pairs['values'] as $key => $val) {
                if (!$val) continue;

                $comment = $pairs['comments'][$key];
                $params = [
                    'user_id' => $userId,
                    'type' => $type,
                    'value' => $val,
                    'comment' => $comment
                ];
                $this->insert($params);
            }
        }
    }
}