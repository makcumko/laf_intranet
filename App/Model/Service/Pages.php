<?php

namespace App\Model\Service;

class Pages extends AbstractService {
    /** @var \App\Model\Gateway\Pages */
    public $pageGateway;

    function __construct() {
        $this->pageGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Pages");
        parent::__construct();
    }

    public function getById($id) {
        $sql = "SELECT *
                FROM pages
                WHERE flag_deleted = 0
                    AND (id = :id: OR code = :id:)
                LIMIT 1";
        return $this->db->query($sql, ['id' => $id], \App\Model\DB\AbstractDBAdapter::FETCH_LINE);
    }

    public function getAll() {
        return $this->pageGateway->filter([], ['code' => 'ASC']);
    }
}