<?php

namespace Katsana\Insurance\Tests;

use Katsana\Insurance\Signature;
use Laravie\Codex\Testing\Faker;

class SignatureTest extends TestCase
{
    /** @test */
    public function it_can_verify_response()
    {
        $now = time();
        $payload = '{"device_id":123,"event":"Device+mobilized"}';

        $response = $this->getMessageWithPayload('secret', $payload);

        $signature = new Signature('secret');
        $this->assertTrue($signature->verifyFrom($response));
    }

    /** @test */
    public function it_cant_verify_response_given_invalid_signature()
    {
        $now = time();
        $payload = '{"device_id":123,"event":"Device+mobilized"}';

        $response = $this->getMessageWithPayload('secret!', $payload);

        $signature = new Signature('secret');
        $this->assertFalse($signature->verifyFrom($response));
    }

    /**
     * Get mocked message with payload.
     *
     * @param int $timestamp
     *
     * @return \Mockery\MockeryInterface
     */
    protected function getMessageWithPayload(string $key, string $payload)
    {
        $message = Faker::create()->message();
        $signature = hash_hmac('sha256', $payload, $key);

        $message->shouldReceive('getHeader')->with('X-Insurance-Signature')->andReturn([
            $signature,
        ])->shouldReceive('getBody')->andReturn($payload);

        return $message;
    }
}
