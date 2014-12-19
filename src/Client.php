<?php

namespace PixelAzul;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as Guzzle;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;
use PixelAzul\AbstractProvider;

class Client
{
    private $key;
    private $guzzle;
    private $cache;
    private $providedMethods = [];

    const CACHE_LIFETIME = 600;
	const API_VERSION = 1;

    public function __construct(ClientInterface $guzzle, $token)
    {
        $this->token = $token;
        $this->guzzle = $guzzle;
        $this->registerProviders();
    }

    public function __call($method, $args)
    {
        $provider = $this->getProviderByMethod($method);
        return call_user_func_array([$provider, $method], $args);
    }

    public function request($method, $url = null, array $options = [])
    {
        $cacheKey = sha1(serialize(func_get_args()));

        $data = $this->getCache()->fetch($cacheKey);
        if (false === $data) {
            $request = $this->guzzle->createRequest($method, $url, [
	                'User-Agent'    => 'Pixel Azul PHP Client',
	                'Authorization' => "pixel {$this->token}",
	                'Accept'        => 'application/json'
	            ]);
            $data = $this->guzzle->send($request)->json();
            $this->getCache()->save($cacheKey, $data, static::CACHE_LIFETIME);
        }

        return $data;
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

    public function getProviders()
    {
        return ['Ping', 'Course'];
    }

    protected function registerProviders()
    {
        foreach ($this->getProviders() as $provider) {
            $fqcn = "PixelAzul\\Provider\\{$provider}";
            $this->registerProvider($fqcn);
        }
    }

    public function registerProvider($fqcn)
    {

        if (!class_exists($fqcn)) {
            throw new \InvalidArgumentException("provider \"{$fqcn}\" not found");
        }

        $provider = new $fqcn($this);

        if (!$provider instanceof AbstractProvider) {
            throw new \InvalidArgumentException("Invalid provider \"{$fqcn}\"");
        }

        foreach ($provider->getMethods() as $method) {
            $this->providedMethods[$method] = $provider;
        }
    }

    protected function getProviderByMethod($method)
    {
        if (!array_key_exists($method, $this->providedMethods)) {
            throw new \RuntimeException("There is no provider for the method \"{$method}\"");
        }

        return $this->providedMethods[$method];
    }

    protected function initializeCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new ArrayCache();
        }
    }

    public static function factory($token, array $options = [])
    {

        if (!isset($options['endpoint']) || !$options['endpoint']) {
            $options['endpoint'] = 'https://api.pixelazul.com.br/v' . static::API_VERSION . '/';
        }

        $guzzle = new Guzzle($options['endpoint'], [
            'allow_redirects' => false
        ]);

        $client = new static($guzzle, $token);

        if (array_key_exists('cache', $options)) {
            $client->setCache($options['cache']);
        }

        return $client;
    }
}