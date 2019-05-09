KATSANA Insurance Renewal SDK for PHP
==============

[![Build Status](https://travis-ci.org/katsana/insurance-sdk-php.svg?branch=master)](https://travis-ci.org/katsana/insurance-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/katsana/insurance-sdk-php/v/stable)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![Total Downloads](https://poser.pugx.org/katsana/insurance-sdk-php/downloads)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/katsana/insurance-sdk-php/v/unstable)](https://packagist.org/packages/katsana/insurance-sdk-php)
[![License](https://poser.pugx.org/katsana/insurance-sdk-php/license)](https://packagist.org/packages/katsana/insurance-sdk-php)

* [Installation](#installation)
* [Getting Started](#getting-started)
    - [Creating Client](#creating-client)
    - [Handling Response](#handling-response)
    - [Using the API](#using-the-api)

## Installation

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

## Getting Started

### Creating Client

You can start by creating a client by using the following code (which uses `php-http/guzzle6-adapter` and `php-http/discovery` to automatically pick available adapter installed via composer):

```php
<?php

use Katsana\Insurance\Client;

$sdk = Client::make('client-id', 'client-secret');
```

In most cases, you will be using the client with existing Access Token. You can initiate the client using the following code:

```php
<?php

use Katsana\Insurance\Client;

$sdk = Client::fromAccessToken('access-token');
```

### Handling Response

Every API request using the API would return an instance of `Katsana\Insurance\Response` which can fallback to `\Psr\Http\Message\ResponseInterface`, this allow developer to further inspect the response. 

As an example:

```php
$response = $sdk->uses('Insurer')->all();

var_dump($response->toArray());
```

#### Getting the Response

You can get the raw response using the following:

```php
$response->getBody();
```

However we also create a method to parse the return JSON string to array.

```php
$response->toArray();
```

#### Checking the Response HTTP Status

You can get the response status code via:

```php
$response->getStatusCode();

$response->isSuccessful();

$response->isUnauthorized();
```

#### Checking the Response Header

You can also check the response header via the following code:

```php
$response->getHeaders(); // get all headers as array.
$response->hasHeader('Content-Type'); // check if `Content-Type` header exist.
$response->getHeader('Content-Type'); // get `Content-Type` header.
```

### Using the API

There are two way to request an API:

#### Using API Resolver

This method allow you as the developer to automatically select the current selected API version without having to modify the code when KATSANA release new API version.

```php
$insurer = $sdk->uses('Insurer'); 

$response = $insurer->all(); 
```

> This would resolve an instance of `Katsana\Insurance\One\Insurer` class (as `v1` would resolve to `One` namespace).

#### Explicit API Resolver

This method allow you to have more control on which version to be used.

```php
$insurer = $sdk->via(new Katsana\Insurance\One\Insurer());

$response = $insurer->all();
```
