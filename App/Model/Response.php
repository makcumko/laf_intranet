<?php
namespace App\Model;

class Response
{
    protected $content = array();

    public function __construct(array $content = null)
    {
        if (isset($content))
        {
            $this->content = $content;
        }
    }

    public function add($param, $value = null)
    {
        if (is_array($param)) {
            foreach ($param as $key => $val) $this->content[$key] = $val;
        } elseif ($param) {
            $this->content[$param] = $value;
        }
    }

    public function getData()
    {
        return $this->content;
    }

    public function Output($format) {
        /** @var \App\Model\Data\Protocol\AbstractProtocol  */
        $protocol = Registry::Singleton("\\App\\Model\\Data\\Protocol\\".caseXx($format)."Protocol");
        echo $protocol->display($this->content);
    }

}

