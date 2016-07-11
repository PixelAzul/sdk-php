<?php

namespace PixelAzul\Provider;

use PixelAzul\AbstractProvider;

class Course extends AbstractProvider
{

    var $ignoreLists = [];

    public function get($slug)
    {
        return $this->getClient()->request('GET', "course/{$slug}");
    }

    public function select($args, $ignoreList)
    {
        $ignores = $this->getIgnoredItems($ignoreList);

        if ($ignores) {
            $args['ignore'] = $ignores;
        }

        $data = $this->getClient()->request('GET', "course", $args);
        $this->addToIgnoredItems($data->data, $ignoreList);
        return $data;
    }

    public function last($args)
    {
        return $this->getClient()->request('GET', "course/last", $args);
    }

    private function getIgnoredItems($ignoreList)
    {
        $ids = [];
        if ($ignoreList) {
            if (!isset($this->ignoreLists[$ignoreList])) {
                $this->ignoreLists[$ignoreList] = [];
            }
            $ids = $this->ignoreLists[$ignoreList];
        }

        return implode($ids, ',');
    }

    private function addToIgnoredItems($data, $ignoreList)
    {
        if (!$ignoreList) {
            return;
        }

        foreach ($data as $item) {
            $this->ignoreLists[$ignoreList][] = $item->id;
        }
    }
}