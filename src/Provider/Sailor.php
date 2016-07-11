<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Sailor extends AbstractProvider
{
    public function getSailorDetails($slug)
    {
        return $this->getClient()->request('GET', "sailor/{$slug}");
    }

    public function getSailorReviews($slug)
    {
        return $this->getClient()->request('GET', "sailor/{$slug}/review");
    }

    public function getSailorFollowers($slug)
    {
        return $this->getClient()->request('GET', "sailor/{$slug}/followers");
    }

    public function getSailorProjects($slug)
    {
        return $this->getClient()->request('GET', "sailor/{$slug}/projects");
    }

    public function getSailorJobs($slug)
    {
        return $this->getClient()->request('GET', "sailor/{$slug}/jobs");
    }
}