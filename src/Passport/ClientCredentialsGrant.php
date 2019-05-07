<?php

namespace Katsana\Insurance\Passport;

use InvalidArgumentException;
use Katsana\Insurance\Request;
use Laravie\Codex\Contracts\Response;
use RuntimeException;

class ClientCredentialsGrant extends Request
{
    /**
     * Create access token.
     *
     * @param string $username
     * @param string $password
     * @param string|null $scope
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function authenticate(?string $scope = '*'): Response
    {
        $body = $this->mergeApiBody(
            \array_filter(\compact('scope'))
        );

        return $this->send('POST', 'oauth/token', $this->getApiHeaders(), $body)
                    ->validateWith(function ($statusCode, $response) use ($body, $scope) {
                        if ($statusCode !== 200) {
                            throw new RuntimeException('Unable to generate access token!');
                        }

                        $this->client->setAccessToken($response->toArray()['access_token']);
                    });
    }

    /**
     * Get API Header.
     *
     * @return array
     */
    protected function getApiHeaders(): array
    {
        return [
            'Accept' => "application/vnd.insure.v1+json",
            'Content-Type' => "application/json",
        ];
    }

    /**
     * Get API Body.
     *
     * @return array
     */
    protected function getApiBody(): array
    {
        $clientId = $this->client->getClientId();
        $clientSecret = $this->client->getClientSecret();

        if (empty($clientId) || empty($clientSecret)) {
            throw new InvalidArgumentException('Missing client_id and client_secret information!');
        }

        return [
            'scope' => '*',
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];
    }
}
