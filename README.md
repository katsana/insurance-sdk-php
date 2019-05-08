KATSANA Insurance Renewal SDK for PHP
==============

[![Build Status](https://travis-ci.org/katsana/insurance-sdk-php.svg?branch=master)](https://travis-ci.org/katsana/insurance-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/katsana/insurance-sdk-php/v/stable)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![Total Downloads](https://poser.pugx.org/katsana/insurance-sdk-php/downloads)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/katsana/insurance-sdk-php/v/unstable)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![License](https://poser.pugx.org/katsana/insurance-sdk-php/license)](https://packagist.org/packages/katsana/insurance-sdk-php)

* [Installation](#installation)
* [Usages](#usages)

## Insurance

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "katsana/insurance-sdk-php": "^1.0",
        "php-http/guzzle6-adapter": "^2.0"
    }
}
```

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "php-http/guzzle6-adapter" "katsana/insurance-sdk-php=^1.0"

### HTTP Adapter

Instead of utilizing `php-http/guzzle6-adapter` you might want to use any other adapter that implements `php-http/client-implementation`. Check [Clients & Adapters](http://docs.php-http.org/en/latest/clients.html) for PHP-HTTP.

## Usages

### Creating Client

You can start by creating a client by using the following code (which uses `php-http/guzzle6-adapter` and `php-http/discovery` to automatically pick available adapter installed via composer):

```php
<?php

use Katsana\Insurance\Client;

$katsana = Client::make('client-id', 'client-secret');
```

In most cases, you will be using the client with existing Access Token. You can initiate the client using the following code:

```php
<?php

use Katsana\Insurance\Client;

$katsana = Client::fromAccessToken('access-token');
```
