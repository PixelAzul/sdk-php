<?php

namespace PixelAzul;

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

}