<?php
namespace App\Model\Data;

class MethodData extends \App\Model\Data\AbstractData
{
    protected $source = '';

    function __construct($controller, $load = false)
    {
        $this->source = 'Methods/'.$controller.'.json';
        parent::__construct($load);
//        $this->loadContainer();

    }

    protected function loadContainer()
    {
        if (!isset($this->container)) {
            parent::loadContainer();

            // parsing keywords for vars
            foreach ($this->container as $method => $vars) {
                foreach ($vars as $varName => $description) {
                    $varType = "text";
                    $varRequired = false;
                    $varDefault = null;
                    $keywords = explode(" ", $description);
                    foreach ($keywords as $nr => $keyword) {
                        switch (strtolower($keyword)) {
                            case "required":
                            case "req":
                            case "*":
                                $varRequired = true; break;
                            case "int":
                            case "integer":
                            case "number":
                                $varType = "number"; break;
                            case "date":
                            case "datetime":
                                $varType = "datetime"; break;
                        }

                        if (strtolower($keyword) == 'default') {
                            $varDefault = implode(" ", array_slice($keywords, $nr + 1));
                            break;
                        }
                    }

                    $this->container[$method][$varName] = [
                        "type" => $varType,
                        "required" => $varRequired,
                        "default" => $varDefault
                    ];
                }
            }
        }

//        var_dump($this->container);

    }
}