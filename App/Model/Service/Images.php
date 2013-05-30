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

    function getImageResized($id, $maxWidth = 0, $maxHeight = 0) {
        // TODO
        echo "TODO";
    }
}