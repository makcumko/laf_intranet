<?php
namespace App\Model\Data\Protocol;

interface AbstractProtocol {
    public function getRequestParams($request);

    public function display($data);
}