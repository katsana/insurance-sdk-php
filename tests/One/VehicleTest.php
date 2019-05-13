<?php

namespace Katsana\Insurance\Tests\One;

use Faker\Factory as FakerFactory;
use Katsana\Insurance\Tests\TestCase;
use Laravie\Codex\Testing\Faker;

class VehicleTest extends TestCase
{
    /** @test */
    public function it_can_update_vehicle_information()
    {
        $generator = FakerFactory::create('ms_MY');

        $headers = [
            'Accept' => 'application/vnd.insure.v1+json',
            'Authorization' => 'Bearer '.static::ACCESS_TOKEN,
        ];

        $plateNumber = $generator->jpjNumberPlate;
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
        $insuranceInformation = [
            'ended_at' => '2019-05-08 16:00:00',
        ];

        $payload = array_merge([
            'plate_number' => $plateNumber,
            'owner' => $ownerInformation,
            'insurance' => $insuranceInformation,
        ], $vehicleInformation);

        $faker = Faker::create()
                        ->sendJson('POST', $headers, $payload)
                        ->expectEndpointIs('https://api.insure.katsana.com/vehicles?include=customer')
                        ->shouldResponseWith(200, '{}');

        $response = $this->makeClientWithAccessToken($faker)
                        ->uses('Vehicle')
                        ->save($plateNumber, $vehicleInformation, $ownerInformation, $insuranceInformation);
    }
}
