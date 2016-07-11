<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Newsletter extends AbstractProvider
{

    public function sign($args)
    {
        return $this->getClient()->post('newsletter/subscribe', $args);
    }

}