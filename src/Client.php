<?php

namespace PixelAzul;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as Guzzle;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;

class Client
{
    private $key;
    private $guzzle;
    private $cache;

    const CACHE_LIFETIME = 600;

    public function __construct(ClientInterface $guzzle, $key)
    {
        $this->key = $key;
        $this->guzzle = $guzzle;
    }

    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function getCache()
    {
        $this->initializeCache();
        return $this->cache;
    }

    protected function initializeCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new ArrayCache();
        }
    }

    public function request($method, $url = null, array $options = [])
    {
        $cacheKey = sha1(serialize(func_get_args()));

        $data = $this->getCache()->fetch($cacheKey);
        if (false === $data) {
            $request = $this->guzzle->createRequest($method, $url, $options);
            $data = $this->guzzle->send($request)->json();
            $this->getCache()->save($cacheKey, $data, static::CACHE_LIFETIME);
        }

        return $data;
    }

    public static function factory($key, array $options = [])
    {
        $guzzle = new Guzzle([
            'allow_redirects' => false,
            'base_url' => 'https://api.pixelazul.com.br',
            'headers' => [
                'User-Agent' => 'Pixel Azul PHP Client',
                'Authorization' => "pixel $key"
            ]
        ]);

        $client = new static($guzzle, $key);

        if (array_key_exists('cache', $options)) {
            $this->setCache($options['cache']);
        }

        return $client;
    }
}