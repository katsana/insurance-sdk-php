<?php

namespace Katsana\Insurance\Tests\Passport;

use Katsana\Insurance\Client;
use Katsana\Insurance\Passport\ClientCredentialsGrant;
use Katsana\Insurance\Response;
use Katsana\Insurance\Tests\TestCase;
use Laravie\Codex\Testing\Faker;

class ClientCredentialsGrantTest extends TestCase
{
    /** @test */
    public function it_can_authenticate()
    {
        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Content-Type' => 'application/json',
        ];

        $faker = Faker::create()
                        ->sendJson('POST', $headers, '{"scope":"*","grant_type":"client_credentials","client_id":"homestead","client_secret":"secret"}')
                        ->expectEndpointIs('https://api.insure.katsana.com/oauth/token')
                        ->shouldResponseWith(200, '{"access_token":"secret"}');

        $client = $this->makeClientWithAccessToken($faker);

        $response = $client->via(new ClientCredentialsGrant())->authenticate();

        $data = $response->toArray();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('secret', $data['access_token']);
        $this->assertSame('secret', $client->getAccessToken());
    }

    /** @test */
    public function it_cant_authenticate_when_response_is_not_200()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Unable to generate access token!');

        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Content-Type' => 'application/json',
        ];

        $faker = Faker::create()
                        ->sendJson('POST', $headers, '{"scope":"*","grant_type":"client_credentials","client_id":"homestead","client_secret":"secret"}')
                        ->expectEndpointIs('https://api.insure.katsana.com/oauth/token')
                        ->shouldResponseWith(403, '{}');

        $client = $this->makeClientWithAccessToken($faker);

        $response = $client->via(new ClientCredentialsGrant())->authenticate();
    }

    /** @test */
    public function it_cant_attempt_to_authenticate_without_client_id_and_secret()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Missing client_id and client_secret information!');

        $client = Client::fromAccessToken('foo');

        $client->via(new ClientCredentialsGrant())->authenticate();
    }
}
