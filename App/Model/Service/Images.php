<?php
namespace App\Model\Service;

use App\Model\Registry;

class Images extends AbstractService {
    /** @var \App\Model\Gateway\Images */
    public $imagesGateway;

    function __construct() {
        $this->imagesGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Images");
        parent::__construct();
    }

    function UploadImage($file) {
        if (!getimagesize($file['tmp_name'])) return false;

        $filename = uniqid()."_".$file['name'];
        $targetFile = ROOT_DIR."www/upload/Images/".$filename;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $params = [
                'filename' => $filename,
                'author_id' => $this->user['id']
            ];
            return $this->imagesGateway->insert($params);
        }
        return false;
    }

    function GetImageResized($id, $maxWidth = 0, $maxHeight = 0) {
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
//        header('Content-Type: image/jpeg');
        readfile($resultFile);
        die();
    }
}