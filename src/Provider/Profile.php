<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Profile extends AbstractProvider
{
    public function instructor($slug)
    {
        return $this->getClient()->request('GET', "instructor/{$slug}");
    }

    public function student($slug)
    {
        return $this->getClient()->request('GET', "student/{$slug}");
    }
}