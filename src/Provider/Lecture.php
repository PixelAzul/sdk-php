<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Lecture extends AbstractProvider
{
    public function getLectureList($args)
    {
        $query = $this->generateUrlArgs($args, ['pagesize', 'page']);
        return $this->getClient()->request('GET', 'lecture' . $query);
    }
}