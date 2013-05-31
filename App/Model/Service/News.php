<?php

namespace App\Model\Service;

class News extends AbstractService {
    /** @var \App\Model\Gateway\News */
    public $newsGateway;
    /** @var \App\Model\Gateway\Users */
    public $usersGateway;
    /** @var \App\Model\Gateway\Comments */
    public $commentsGateway;

    const NEWS_PAGESIZE = 20;

    function __construct() {
        $this->newsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\News");
        $this->usersGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        $this->commentsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Comments");
        parent::__construct();
    }

    public function getPaged($page = 1) {
        $news = $this->newsGateway->filterPaged([], ['id' => 'DESC'], self::NEWS_PAGESIZE, self::NEWS_PAGESIZE * ($page - 1));
        foreach ($news['items'] as &$row) {
            $author = $this->usersGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
            $row['author_avatar_id'] = $author['avatar_id'] ?: $author['avatar_id'];
            $row['comments'] = $this->getComments($row['id']);
        }

        return $news;
    }

    public function create($title, $text) {
        $data = [
            'title' => $title,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->newsGateway->insert($data);
    }

    public function getComments($id) {
        $comments = $this->commentsGateway->filter(['type' => 'news', 'source_id' => $id], ['id' => 'DESC']);
        foreach ($comments as &$row) {
            $author = $this->usersGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
            $row['author_avatar_id'] = $author['avatar_id'] ?: $author['avatar_id'];
        }
        return $comments;
    }

    public function addComment($id, $text) {
        $data = [
            'type' => 'news',
            'source_id' => $id,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->commentsGateway->insert($data);
    }
}