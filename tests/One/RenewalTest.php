<?php

namespace Katsana\Insurance\Tests\One;

use Faker\Factory as FakerFactory;
use Katsana\Insurance\Response;
use Katsana\Insurance\Tests\TestCase;
use Laravie\Codex\Testing\Faker;

class RenewalTest extends TestCase
{
    /** @test */
    public function it_can_make_insurance_renewal_payment()
    {
        $generator = FakerFactory::create('ms_MY');

        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Authorization' => 'Bearer '.static::ACCESS_TOKEN,
        ];

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $sumCovered = 35000;
        $addons = [];
        $declarations = ['pds' => true, 'ind' => true, 'pdpa' => true, 'lapse' => false];

        $payload = [
            'sum_covered' => $sumCovered,
            'addons' => $addons,
            'declarations' => $declarations,
        ];

        $faker = Faker::create()
                        ->sendJson('POST', $headers, $payload)
                        ->expectEndpointIs("https://api.insure.katsana.com/quotations/{$plateNumber}/{$insurerCode}/pay")
                        ->shouldResponseWith(200, '{}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Renewal')
                        ->pay($plateNumber, $insurerCode, $sumCovered, $addons, $declarations);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_cant_make_insurance_renewal_payment_without_access_token()
    {
        $this->expectException('Katsana\Insurance\Exceptions\MissingAccessToken');
        $this->expectExceptionMessage('Request requires valid access token to be available!');

        $generator = FakerFactory::create('ms_MY');

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $sumCovered = 35000;
        $addons = [];
        $declarations = ['pds' => true, 'ind' => true, 'pdpa' => true, 'lapse' => false];

        $payload = [
            'sum_covered' => $sumCovered,
            'addons' => $addons,
            'declarations' => $declarations,
        ];

        $faker = Faker::create();

        $this->makeClient($faker)
            ->uses('Renewal')
            ->pay($plateNumber, $insurerCode, $sumCovered, $addons, $declarations);
    }
}
