<?php
namespace App\Model\Service;

use App\Model\Registry;

class Images extends AbstractService {
    /** @var \App\Model\Gateway\Images */
    public $imagesGateway;
    /** @var \App\Model\Gateway\Users */
    public $userGateway;
    /** @var \App\Model\Gateway\Comments */
    public $commentsGateway;


    const GALLERY_PAGESIZE = 30;

    public function __construct() {
        $this->imagesGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Images");
        $this->userGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        $this->commentsGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Comments");
        parent::__construct();
    }

    public function UploadImage($file, $description = "") {
        if (!getimagesize($file['tmp_name'])) return false;

        $filename = uniqid()."_".$file['name'];
        $targetFile = ROOT_DIR."www/upload/Images/".$filename;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $params = [
                'filename' => $filename,
                'author_id' => $this->user['id'],
                'description' => $description
            ];
            return $this->imagesGateway->insert($params);
        }
        return false;
    }

    public function GetImageResized($id, $maxWidth = 0, $maxHeight = 0) {
        $image = $this->imagesGateway->read($id);

        if (empty($image)) {
            $source = ROOT_DIR."www/upload/Images/empty.jpg";
        } else {
            $source = ROOT_DIR."www/upload/Images/".$image['filename'];
        }

        $width = intval($maxWidth);
        $height = intval($maxHeight);

        if (!$width && !$height) {
            $resultFile = $source;
        } else {
            // need resize
            $resultFile = preg_replace("/(.*)\.(\w+)/is", "$1.{$width}x{$height}.$2", $source);
            if (!is_file($resultFile)) {
                \ImageResizer::makeResizedPhoto($source, $resultFile, $width, $height);
            }
        }

        ob_end_clean();
        header('Content-Type: image/jpeg');
        readfile($resultFile);
        die();
    }


    public function GetList($page = 1, $order = null, $filterParam = null) {
        if (empty($order)) $order = ["id" => "DESC"];
        $filter = [];

        if (!empty($filterParam)) {
            foreach ($filterParam as $key => $val) {
                switch (strtolower($key)) {
                    case "user":
                    case "user_id":
                    case "author_id":
                        $filter['author_id'] = $val;
                        break;
                    case "tag":
                        $filter['description|like'] = "%#{$val}%";
                        break;
                }
            }
        }

        $images = $this->imagesGateway->filterPaged($filter, $order, self::GALLERY_PAGESIZE, self::GALLERY_PAGESIZE * ($page - 1));
        foreach ($images['items'] as &$row) {
            $author = $this->userGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
            $row['author_avatar_id'] = $author['avatar_id'] ?: $author['avatar_id'];
        }
        return $images;
    }

    public function GetData($id) {
        $result = $this->imagesGateway->read($id);
        $author = $this->userGateway->read($result['author_id']);
        $result['author'] = $author['shortname'] ?: $author['fullname'];
        $result['author_avatar_id'] = $author['avatar_id'] ?: $author['avatar_id'];
        return $result;
    }

    public function getComments($id) {
        $comments = $this->commentsGateway->filter(['type' => 'image', 'source_id' => $id], ['id' => 'DESC']);
        foreach ($comments as &$row) {
            $author = $this->userGateway->read($row['author_id']);
            $row['author'] = $author['shortname'] ?: $author['fullname'];
            $row['author_avatar_id'] = $author['avatar_id'] ?: $author['avatar_id'];
        }
        return $comments;
    }

    public function addComment($id, $text) {
        $data = [
            'type' => 'image',
            'source_id' => $id,
            'text' => $text,
            'author_id' => $this->user['id']
        ];
        return $this->commentsGateway->insert($data);
    }
}