<?php
namespace App\Model\Service;

use App\Model\Registry;

class Ideas extends AbstractService {
    /** @var \App\Model\Gateway\Ideas */
    public $ideasGateway;
    /** @var \App\Model\Gateway\IdeaVotes */
    public $ideaVotesGateway;
    /** @var \App\Model\Gateway\Users */
    public $usersGateway;
    /** @var \App\Model\Gateway\Comments */
    public $commentsGateway;

    const IDEAS_PAGESIZE = 20;

    function __construct() {
        $this->ideasGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Ideas");
        $this->ideaVotesGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\IdeaVotes");
        $this->usersGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        $this->commentsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Comments");

        parent::__construct();
    }

    public function GetList($page = 1) {
        $news = $this->ideasGateway->filterPaged([], ['id' => 'DESC'], self::IDEAS_PAGESIZE, self::IDEAS_PAGESIZE * ($page - 1));
        foreach ($news['items'] as &$row) {
            $author = $this->usersGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
            $row['comments'] = $this->getComments($row['id']);
            $row['votes'] = $this->getVotes($row['id']);
        }

        return $news;
    }

    public function Create($title, $text) {
        $data = [
            'title' => $title,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->ideasGateway->insert($data);
    }

    public function getComments($id) {
        $comments = $this->commentsGateway->filter(['type' => 'idea', 'source_id' => $id], ['id' => 'DESC']);
        foreach ($comments as &$row) {
            $author = $this->usersGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
        }
        return $comments;
    }

    public function addComment($id, $text) {
        $data = [
            'type' => 'idea',
            'source_id' => $id,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->commentsGateway->insert($data);
    }

    public function registerVote($id, $yes = true) {
        $existing = current($this->ideaVotesGateway->filter(['user_id' => $this->user['id'], 'idea_id' => $id]));

        if (empty($existing)) {
            $params = [
                "user_id" => $this->user['id'],
                "idea_id" => $id,
                "flag_yes" => $yes,
            ];
            $this->ideaVotesGateway->insert($params);
        } elseif ($existing['flag_yes'] != $yes) {
            $this->ideaVotesGateway->update($existing['id'], ["flag_yes" => $yes]);
        }
    }


    public function getVotes($id) {
        $sql = "SELECT v.flag_yes, COUNT(v.id) cnt, GROUP_CONCAT(u.shortname) list
                FROM idea_votes v
                LEFT JOIN users u ON u.id = v.user_id
                WHERE v.flag_deleted = 0
                  AND u.flag_deleted = 0
                  AND v.idea_id = :0:
                GROUP BY v.flag_yes";
        $rows = $this->db->query($sql, $id);

        $result = [
            "yes" => 0,
            "no" => 0,
            "total" => 0,
            "yeslist" => "",
            "nolist" => ""
        ];
        if ($rows) foreach ($rows as $row) {
            if ($row['flag_yes']) {
                $result['yes'] = $row['cnt'];
                $result['yeslist'] = $row['list'];
            }  else {
                $result['no'] = $row['cnt'];
                $result['nolist'] = $row['list'];
            }
            $result['total'] += $row['cnt'];
        }

        return $result;
    }


}