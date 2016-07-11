<?php

namespace PixelAzul;

abstract class AbstractProvider
{

    private $client;

    /**
     * Register the client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the client
     */
    protected function getClient()
    {
        return $this->client;
    }

}