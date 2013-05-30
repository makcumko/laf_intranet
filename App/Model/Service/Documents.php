<?php
namespace App\Model\Service;

use App\Model\Registry;

class Documents extends AbstractService {
    /** @var \App\Model\Gateway\Documents */
    public $documentsGateway;
    /** @var \App\Model\Gateway\Users */
    public $usersGateway;
    /** @var \App\Model\Gateway\Comments */
    public $commentsGateway;

    const DOCUMENTS_PAGESIZE = 20;

    function __construct() {
        $this->documentsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Documents");
        $this->usersGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        $this->commentsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Comments");

        parent::__construct();
    }

    public function GetList($page = 1) {
        return $this->documentsGateway->filterPaged([], ['id' => 'DESC'], self::DOCUMENTS_PAGESIZE, self::DOCUMENTS_PAGESIZE * ($page - 1));
    }


    public function getComments($id) {
        $comments = $this->commentsGateway->filter(['type' => 'document', 'source_id' => $id], ['id' => 'DESC']);
        foreach ($comments as &$row) {
            $author = $this->usersGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
        }
        return $comments;
    }

    public function addComment($id, $text) {
        $data = [
            'type' => 'document',
            'source_id' => $id,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->commentsGateway->insert($data);
    }


}