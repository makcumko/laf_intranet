<?php
namespace App\Model\Data\Protocol;

class JsonProtocol implements AbstractProtocol {
    public function getRequestParams($request) {
        if (isset($source["params"])) return json_decode($source["params"], true);
        else return [];
    }

    public function display($data) {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | FLAG_DEBUG ? JSON_PRETTY_PRINT : 0);
    }
}