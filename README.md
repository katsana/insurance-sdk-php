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
* [Usages](#usages)
    - [Get List of Insurers](#get-list-of-insurers)
    - [Save Vehicle Information](#save-vehicle-information)
    - [Make Payment](#make-payment)

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

##### Authenticate Using Client Credential Grant

Once you initiate the client, you need to get an Access Token before using the services. All you need to do is:

```php
$passport = $sdk->authenticate();

$accessToken = $passport->toArray()['access_token'];

$sdk->setAccessToken($accessToken); // The `authenticate` method does this automatically.

```

Additionally, if you already have an Access Token, you can initiate a client with existing Access Token. You can initiate the client using the following code:

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

## Usages

### Get List of Insurers

Use this API to get a complete list of available Insurers.

#### SDK Query

```php
$insurer = $sdk->uses('Insurer');

$response = $insurer->all();

var_dump($response->toArray());
```

#### Response Parameters

| Parameters    | Type             | Description
| :-------------| :--------------- |:--------------
| `country_code`| string           | The country code, e.g: `MY`
| `name`        | string           | Insurer name
| `partner`     | boolean          | Whether we can make renewal
| `product_code`| string&#x7c;null | Product code

##### Response Sample 

```json
{
  "data": [{
    "country_code": "MY",
    "name": "RHB Insurance",
    "partner": false,
    "product_code": null
  },
  {
    "country_code":"MY",
    "name":"Allianz Malaysia Berhad",
    "partner":true,
    "product_code":null
  },
  {
    "country_code":"MY",
    "name":"Takaful Ikhlas",
    "partner":false,
    "product_code":null
  }]
}
```
### Save Vehicle Information

Use this API to store and update Vehicle infromation.

```php
$vehicles = $sdk->uses('Vehicle');

$response = $sdk->save(
  $plateNumber,
  $vehicleInformation = [
    'chasis_number' => $chasisNumber,
    'engine_number' => $engineNumber,
    'year_manufactured' => $yearManufactured,
    'maker' => $makerName,
    'model' => $modelName,
  ],
  $ownerInformation = [
    'fullname' => $ownerFullname,
    'birthdate' => $ownerBirthDate,
    'email' => $ownerEmail,
    'nric' => $ownerNRIC,
    'phone_no' => $ownerPhoneNumber,
    'postcode' => $ownerPostcode,
  ],
  $insuranceInformation = [
    'ended_at' => $insuranceEndedAt
    
  ],
);

var_dump($response->toArray());
```


### Make Payment

Use this API to make a payment for Insurance Renewal.

#### SDK Query

```php
$renewal = $sdk->uses('Renewal');

$response = $renewal->pay(
  $plateNumber, 
  $insurerCode, 
  $sumCovered, 
  $addons = [
    'windscreen' => $windscreenCovered,
    'flood' => false,
    'extended_flood' => false,
    'under_repair_compensation' => false,
    'passenger_negligence_liability' => false,
  ],
  $declaration = [
    'pds' => true,
    'ind' => true,
    'pdpa' => true,
    'lapse' => false, // required to be true if insurance has been lapsed!
  ]
]);

var_dump($response->toArray());
```


