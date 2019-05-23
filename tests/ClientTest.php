<?php

namespace Katsana\Insurance\Tests;

use Katsana\Insurance\Response;
use Laravie\Codex\Testing\Faker;

class ClientTest extends TestCase
{
     /** @test */
    public function it_can_set_endpoint_to_sandbox()
    {
        $client = $this->makeClient(Faker::create());
        $client->useSandbox();

        $this->assertSame('https://api.insure-staging.katsanalabs.com', $client->getApiEndpoint());
    }

    /** @test */
    public function it_can_authenticate_using_passport()
    {
        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Content-Type' => 'application/json',
        ];

        $faker = Faker::create()
                        ->sendJson('POST', $headers, '{"scope":"*","grant_type":"client_credentials","client_id":"homestead","client_secret":"secret"}')
                        ->expectEndpointIs('https://api.insure.katsana.com/oauth/token')
                        ->shouldResponseWith(200, '{"access_token":"secret"}');

        $client = $this->makeClient($faker);

        $response = $client->authenticate();

        $data = $response->toArray();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('secret', $data['access_token']);
        $this->assertSame('secret', $client->getAccessToken());
    }
}
