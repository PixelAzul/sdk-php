<?php

namespace PixelAzul;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as Guzzle;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;
use PixelAzul\AbstractProvider;
use \RuntimeException;

class Client
{
    private $key;
    private $guzzle;
    private $cache;
    private $providers = [];

    const CACHE_LIFETIME = 1800;
    const API_VERSION = 1;

    public function __construct(ClientInterface $guzzle, $token)
    {
        $this->token = $token;
        $this->guzzle = $guzzle;
    }

    public function __get($provider)
    {
        return $this->getProvider($provider);
    }

    public function request($method, $url = null, $args = [])
    {
        $args = (array)$args;

        $cacheKey = sha1(serialize(func_get_args()));
        $data = $this->getCache()->fetch($cacheKey);

        if (false === $data) {
            try {
                if ($args) {
                    $url .= $this->generateUrlArgs($args, ['pageSize', 'page', 'ignore', 'courseType']);
                }

                $request = $this->guzzle->createRequest($method, $url, [
                    'User-Agent' => 'Pixel Azul PHP Client',
                    'Authorization' => "pixel {$this->token}",
                    'Accept' => 'application/json'
                ]);

                $data = json_decode((string)$this->guzzle->send($request)->getBody(), false);
            } catch (Exception $e) {
                throw new RuntimeException("Falha ao consultar dados na API: $url");
            }

            if (null === $data) {
                throw new RuntimeException("No data returned from the API: $url");
            }

            $this->getCache()->save($cacheKey, $data, static::CACHE_LIFETIME);
        }

        return $data;
    }

    public function post($url = null, $args = [])
    {
        $args = (array)$args;

        try {
            $request = $this->guzzle->createRequest('POST', $url, [
                'User-Agent' => 'Pixel Azul PHP Client',
                'Authorization' => "pixel {$this->token}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]);
            $request->setBody(json_encode($args));

            return $this->guzzle->send($request)->json();

        } catch (Exception $e) {
            throw new RuntimeException("Falha ao consultar dados na API: $url");
        }
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

    protected function getProvider($name)
    {

        $name = ucfirst($name);

        $fqcn = "PixelAzul\\Provider\\{$name}";

        if (array_key_exists($fqcn, $this->providers)) {
            return $this->providers[$fqcn];
        }

        if (!class_exists($fqcn)) {
            throw new \InvalidArgumentException("provider \"{$fqcn}\" not found");
        }

        $provider = new $fqcn($this);

        if (!$provider instanceof AbstractProvider) {
            throw new \InvalidArgumentException("Invalid provider \"{$fqcn}\"");
        }

        $this->providers[$fqcn] = $provider;

        return $provider;
    }

    protected function initializeCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new ArrayCache();
        }
    }

    public static function factory($token, array $options = [])
    {
        if (!isset($options['endpoint']) && $options['endpoint']) {
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

    private function toObject($var) {

    }

    private function generateUrlArgs(array $arguments, array $filter)
    {
        $result = [];

        foreach ($arguments as $name => $value) {
            if (in_array($name, $filter)) {
                $result[$name] = $value;
            }
        }

        if (!$result) {
            return '';
        }

        ksort($result);

        return '?' . http_build_query($result);
    }
}