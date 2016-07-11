<?php

namespace PixelAzul;

class Item
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($param)
    {

        if (!isset($this->data[$param])) {
            return null;
        }

        if (is_array($this->data[$param]) &&
            (isset($this->data[$param][0]) || count($this->data[$param]) == 0))
        {
            $this->data[$param] = new ArrayCollection($this->data[$param]);
        }

        if (is_array($this->data[$param])) {
            $this->data[$param] = new Item($this->data[$param]);
        }

        return$this->data[$param];
    }

    public function __call($method, $args)
    {
        if ('get' == substr($method, 0, 3)) {
            $param = lcfirst(substr($method, 3));
            return $this->$param;
        }
    }

    public function __toString()
    {
        return $this->data;
    }

}