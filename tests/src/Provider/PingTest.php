<?php

namespace PixelAzul\Provider;

class PingTest extends \PHPUnit_Framework_TestCase
{

    public function testPingReturnsPong() {

        $stub = $this->getClientStub();

        $stub->expects($this->once())
             ->method('request')
             ->with(
                $this->equalTo('GET'),
                $this->equalTo('/ping')
             )
             ->willReturn('pong');

        $provider = new Ping($stub);

        $data = $provider->ping();

        $this->assertEquals('pong', $data);
    }

    private function getClientStub()
    {
        return $this->getMockBuilder('\PixelAzul\Client')
                    ->disableOriginalConstructor()
                    ->getMock();
    }


}