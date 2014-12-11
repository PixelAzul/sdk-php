<?php

namespace PixelAzul\Provider;

class CourseTest extends \PHPUnit_Framework_TestCase
{

    public function testListCoursesShouldReturnAnIterator() {

        $stub = $this->getClientStub();

        $stub->expects($this->once())
             ->method('request')
             ->with(
                $this->equalTo('GET'),
                $this->equalTo('/courses/all')
             )
             ->willReturn([]);

        $provider = new Course($stub);

        $data = $provider->listCourses();

        $this->assertEquals([], $data);
    }

    private function getClientStub()
    {
        return $this->getMockBuilder('\PixelAzul\Client')
                    ->disableOriginalConstructor()
                    ->getMock();
    }


}