<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Theme extends AbstractProvider
{
    public function get()
    {
        return $this->getClient()->request('GET', "theme");
    }
}