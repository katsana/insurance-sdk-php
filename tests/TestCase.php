<?php

namespace Katsana\Insurance\Tests;

use Katsana\Insurance\Client;
use Laravie\Codex\Discovery;
use Laravie\Codex\Testing\Faker;
use Mockery as m;
use PHPUnit\Framework\TestCase as PHPUnit;

abstract class TestCase extends PHPUnit
{
    const CLIENT_ID = 'homestead';
    const CLIENT_SECRET = 'secret';
    // const ACCESS_TOKEN = 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF';

    const LATITUDE = 3.0093493;
    const LONGITUDE = 101.5976447;

    /**
     * API Version.
     *
     * @var string
     */
    private $apiVersion;

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();

        Discovery::flush();
    }

    /**
     * Make KATSANA SDK Client.
     *
     * @param \Laravie\Codex\Testing\Faker $faker
     *
     * @return \Katsana\Insurance\Client
     */
    protected function makeClient(Faker $faker): Client
    {
        $client = (new Client($faker->http()))
                        ->setClientId(static::CLIENT_ID)
                        ->setClientSecret(static::CLIENT_SECRET);

        if (! is_null($this->apiVersion)) {
            $client->useVersion($this->apiVersion);
        }

        return $client;
    }

    /**
     * Make KATSANA SDK Client.
     *
     * @param \Laravie\Codex\Testing\Faker $faker
     *
     * @return \Katsana\Insurance\Client
     */
    // protected function makeClientWithAccessToken(Faker $faker): Client
    // {
    //     return $this->makeClient($faker)->setAccessToken(static::ACCESS_TOKEN);
    // }
}
