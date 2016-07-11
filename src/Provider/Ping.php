<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Ping extends AbstractProvider
{

    public function ping()
    {
        return $this->getClient()->request('GET', 'ping');
    }

}