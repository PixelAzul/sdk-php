<?php

namespace PixelAzul;

use \InvalidArgumentException;

class Client
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public static function factory(array $options)
    {
        if (!array_key_exists('key', $options)) {
            throw new \InvalidArgumentException('The "key" param is mandatory');
        }

        return new static($options['key']);
    }
}