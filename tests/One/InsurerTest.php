<?php

namespace Katsana\Insurance\Tests\One;

use Katsana\Insurance\Response;
use Katsana\Insurance\Tests\TestCase;
use Laravie\Codex\Testing\Faker;

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
                        ->shouldResponseWith(200, '{"data":[{"country_code": "MY","name": "RHB Insurance","partner": false,"product_code": null},{"country_code":"MY","name":"Allianz Malaysia Berhad","partner":true,"product_code":null},{"country_code":"MY","name":"Takaful Ikhlas","partner":false,"product_code":null}]}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Insurer')
                        ->all();

        $data = $response->toArray();

        $this->assertInstanceOf(Response::class, $response);
    }
}
