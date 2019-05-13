<?php

namespace Katsana\Insurance\Tests;

use Katsana\Insurance\Client;
use Katsana\Insurance\Request;
use Laravie\Codex\Testing\Faker;

class RequestTest extends TestCase
{
    /** @test */
    public function it_can_throw_exception_when_access_token_is_missing()
    {
        $this->expectException('Katsana\Insurance\Exceptions\MissingAccessToken');
        $this->expectExceptionMessage('Request requires valid access token to be available!');

        $faker = Faker::create();

        $request = new class() extends Request {
            public function foo()
            {
                $this->requiresAccessToken();
            }
        };

        $request->setClient(new Client($faker->http()))->foo();
    }
}
