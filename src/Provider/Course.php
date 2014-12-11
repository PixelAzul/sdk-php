<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Course extends AbstractProvider
{

    public function listCourses()
    {
        return $this->getClient()->request('GET', '/courses/all');
    }

    public function getMethods()
    {
        return [
            'listCourses'
        ];
    }

}