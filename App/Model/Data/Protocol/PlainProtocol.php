<?php
namespace App\Model\Data\Protocol;

class PlainProtocol implements AbstractProtocol {
    public function getRequestParams($request) {
        return [];
    }

    public function display($data) {
        var_dump($data);
    }
}