<?php

namespace PixelAzul;

use Guzzle\Http\Message\Response;
use Doctrine\Common\Cache\ArrayCache;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testClientClassExists()
    {
        $this->assertTrue(class_exists('PixelAzul\Client'));
    }

    public function testClientCanBeCreatedByTheFactory()
    {
        $client = Client::factory('abc');
        $this->assertInstanceOf('PixelAzul\Client', $client);
    }

    public function testGetProvidersMustReturnAnArray()
    {
        $client = $this->createClient();

        $providers = $client->getProviders();

        $this->assertTrue(is_array($providers));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage There is no provider for the method "bla"
     */
    public function testWillThrowAnExceptionIfNoProviderIsFoundForAnGivenMethod()
    {
        $client = $this->createClient();
        $client->bla();
    }

    public function testRequestWillReturnJson()
    {

        $response = new Response(200, null, json_encode('pong'));

        $guzzle = $this->getMockBuilder('\Guzzle\Http\Client')
                    ->disableOriginalConstructor()
                    ->getMock();

        $guzzle->expects($this->once())
                    ->method('send')
                    ->willReturn($response);

        $client = new Client($guzzle, '123');

        // test the response
        $data = $client->ping();
        $this->assertEquals('pong', $data);

        // does the cache works?
        $data = $client->ping();
        $this->assertEquals('pong', $data);
    }

    public function testFactoryWithAnCustomCacheDriver()
    {

        $cacheDriver = new ArrayCache();

        $client = Client::factory('123', [
            'cache' => $cacheDriver
        ]);

        $this->assertSame($cacheDriver, $client->getCache());
    }

    protected function createClient() {
        return Client::factory('abc');
    }

}