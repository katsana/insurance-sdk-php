<?php

namespace Katsana\Insurance\Tests\One;

use Katsana\Insurance\Response;
use Laravie\Codex\Testing\Faker;
use Katsana\Insurance\Tests\TestCase;

class InsurerTest extends TestCase
{
    /** @test */
    public function it_can_list_insurers()
    {
        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Authorization' => 'Bearer '.static::ACCESS_TOKEN,
        ];

        $faker = Faker::create()
                        ->call('GET', $headers)
                        ->expectEndpointIs('https://api.insure.katsana.com/insurers')
                        ->shouldResponseWith(200, '{"data":[]}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Insurer')
                        ->all();

        $data = $response->toArray();

        $this->assertInstanceOf(Response::class, $response);
    }
}
