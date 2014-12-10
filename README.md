# Pixel Azul SDK for PHP

[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/PixelAzul/sdk-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![@Pixel Azul on Twitter](http://img.shields.io/badge/twitter-%40pixelazulweb-blue.svg?style=flat)](https://twitter.com/pixelazulweb)
[![@Pixel Azul on Facebook](http://img.shields.io/badge/facebook-%40pixelazulweb-blue.svg?style=flat)](https://facebook.com/pixelazulweb)
[![Total Downloads](https://img.shields.io/packagist/dt/pixelazul/sdk-php.svg?style=flat)](https://packagist.org/packages/pixelazul/sdk-php)
[![Build Status](https://img.shields.io/travis/pixelazul/sdk-php.svg?style=flat)](https://travis-ci.org/pixelazul/sdk-php)
[![Apache 2 License](https://img.shields.io/packagist/l/pixelazul/sdk-php.svg?style=flat)](http://www.apache.org/licenses/LICENSE-2.0)
[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/PixelAzul/sdk-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

The **Pizel Azul SDK** for PHP enables PHP developers to use [Pixel Azul Services][pixelazul] in their PHP code.

## Quick Example

### List Courses

```php
<?php
require 'vendor/autoload.php';

use PixelAzul\Client;

// Instantiate an Pixel Azul client
$client = Client::factory([
    'key' => 'your token',
    'cache' => 'memcached'
]);

// Query all the courses
$courses = $client->listCourses();

print_r($courses);
```
[pixelazul]: http://aws.amazon.com