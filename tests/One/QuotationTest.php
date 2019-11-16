<?php

namespace Katsana\Insurance\Tests\One;

use Faker\Factory as FakerFactory;
use Katsana\Insurance\Response;
use Katsana\Insurance\Tests\TestCase;
use Laravie\Codex\Testing\Faker;

class QuotationTest extends TestCase
{
    /** @test */
    public function it_can_create_draft_quotation()
    {
        $generator = FakerFactory::create('ms_MY');

        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Authorization' => 'Bearer '.static::ACCESS_TOKEN,
        ];

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $vehicleInformation = [
            'chasis_number' => null,
            'engine_number' => null,
            'year_manufactured' => 2011,
            'maker' => 'Peugeot',
            'model' => '308',
        ];
        $ownerInformation = [
            'fullname' => $generator->name,
            'birthdate' => '1983-04-03',
            'nric' => $generator->myKadNumber(),
            'email' => $generator->email,
            'phone_no' => $generator->mobileNumber,
            'postcode' => $generator->postcode,
        ];
        $sumCovered = null;
        $addons = [];

        $payload = [
            'owner' => $ownerInformation,
            'vehicle' => array_merge($vehicleInformation, ['plate_number' => $plateNumber]),
            'sum_covered' => $sumCovered,
            'addons' => $addons,
        ];

        $faker = Faker::create()
                        ->sendJson('POST', $headers, $payload)
                        ->expectEndpointIs("https://api.insure.drivemark.io/quotations/{$insurerCode}")
                        ->shouldResponseWith(200, '{}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Quotation')
                        ->draft($plateNumber, $insurerCode, $ownerInformation, $vehicleInformation, $sumCovered, $addons);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_cant_create_draft_quotation_without_access_token()
    {
        $this->expectException('Katsana\Insurance\Exceptions\MissingAccessToken');
        $this->expectExceptionMessage('Request requires valid access token to be available!');

        $generator = FakerFactory::create('ms_MY');

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $vehicleInformation = [
            'chasis_number' => null,
            'engine_number' => null,
            'year_manufactured' => 2011,
            'maker' => 'Peugeot',
            'model' => '308',
        ];
        $ownerInformation = [
            'fullname' => $generator->name,
            'birthdate' => '1983-04-03',
            'nric' => $generator->myKadNumber(),
            'email' => $generator->email,
            'phone_no' => $generator->mobileNumber,
            'postcode' => $generator->postcode,
        ];
        $sumCovered = null;
        $addons = [];

        $faker = Faker::create();

        $this->makeClient($faker)
            ->uses('Quotation')
            ->draft($plateNumber, $insurerCode, $ownerInformation, $vehicleInformation, $sumCovered, $addons);
    }

    /** @test */
    public function it_can_create_update_quotation()
    {
        $generator = FakerFactory::create('ms_MY');

        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Authorization' => 'Bearer '.static::ACCESS_TOKEN,
        ];

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $sumCovered = null;
        $addons = [];

        $payload = [
            'sum_covered' => $sumCovered,
            'addons' => $addons,
        ];

        $faker = Faker::create()
                        ->sendJson('PATCH', $headers, $payload)
                        ->expectEndpointIs("https://api.insure.drivemark.io/quotations/{$plateNumber}/{$insurerCode}")
                        ->shouldResponseWith(200, '{}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Quotation')
                        ->update($plateNumber, $insurerCode, $sumCovered, $addons);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_cant_create_update_quotation_without_access_token()
    {
        $this->expectException('Katsana\Insurance\Exceptions\MissingAccessToken');
        $this->expectExceptionMessage('Request requires valid access token to be available!');

        $generator = FakerFactory::create('ms_MY');

        $plateNumber = str_replace(' ', '', $generator->jpjNumberPlate);
        $insurerCode = 'MT';
        $sumCovered = null;
        $addons = [];

        $faker = Faker::create();

        $this->makeClient($faker)
            ->uses('Quotation')
            ->update($plateNumber, $insurerCode, $sumCovered, $addons);
    }
}
