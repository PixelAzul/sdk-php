<?php

namespace PixelAzul;

use \IteratorAggregate;
use \ArrayIterator;
use \Countable;

class MetaCollection implements IteratorAggregate, Countable
{

    private $data;
    private $meta;

    public function __construct($data)
    {
        $this->data = [];
        foreach ($data['Data'] as $item) {
            $this->data[] = new Item($item);
        }
        unset($data['Data']);
        $this->meta = new Item($data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    public function getMeta()
    {
        return $this->meta;
    }

}