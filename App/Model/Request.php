<?php
namespace App\Model;

class Request
{
    public $controller;
    public $method;
    public $format;
    public $params;
    public $extras;

    public function __construct()
    {
        preg_match("/^\/?(\w*)(\/?)(\w*)(\.?)(\w*)(.*)$/is", isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : "", $matches);

        if (!empty($matches)) {
            $this->controller = $matches[2] ? $matches[1] : "";
            $this->method = $matches[2] ? $matches[3] : $matches[1];
            $this->format = $matches[5] ?: 'html';
            $this->extras = array_slice(explode("/", $matches[6]), 1);
        } else {
            throw new \Exception("Bad request format", Errors::ERR_BAD_REQUEST);
        }

//        $this->params = $this->_parseParams($_REQUEST);
        try {
            /** @var \App\Model\Data\Protocol\AbstractProtocol  */
            $protocol = Registry::Singleton("\\App\\Model\\Data\\Protocol\\".caseXx($this->format)."Protocol");
        } catch (\Exception $e) {
            throw new \Exception("Invalid protocol or protocol format", Errors::ERR_INVALID_PROTOCOL);
        }

        $this->params = $_REQUEST;
        foreach ($protocol->getRequestParams($_REQUEST) as $key => $val) $this->params[$key] = $val;

        if (isset($this->params["_"])) unset($this->params["_"]);


    }


}

