<?php

namespace PixelAzul;

use \IteratorAggregate;
use \ArrayIterator;
use \Countable;

class ArrayCollection implements IteratorAggregate, Countable
{

    private $data;

    public function __construct($data)
    {
        $this->data = [];
        foreach ($data as $item) {
            $this->data[] = new Item($item);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    public function count()
    {
        return count($this->data);
    }
}