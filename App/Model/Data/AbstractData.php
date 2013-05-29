<?php
namespace App\Model\Data;

abstract class AbstractData implements \ArrayAccess
{
    protected $source;
    protected $container;

    public function offsetSet($offset, $value) {}
    public function offsetUnset($offset) {}

    protected function loadContainer()
    {
        if (!isset($this->container)) {
            $data = @file_get_contents($this->source, FILE_USE_INCLUDE_PATH);
            if ($data !== false) $this->container = json_decode($data, true);
            else $this->container = [];
        }
    }

    public function __construct($load = false)
    {
        if ($load === true) $this->loadContainer();
    }

    public function offsetExists($offset)
    {
        $this->loadContainer();

        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        $this->loadContainer();

        return isset($this->container[$offset]) ?
            $this->container[$offset] :
            null;
    }

    public function getData()
    {
        $this->loadContainer();
        
        return $this->container;
    }


}
