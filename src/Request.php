<?php

namespace Katsana\Insurance;

use Laravie\Codex\Contracts\Response as ResponseContract;
use Psr\Http\Message\ResponseInterface;

abstract class Request extends \Laravie\Codex\Request
{
    /**
     * Resolve the responder class.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Katsana\Insurance\Response
     */
    protected function responseWith(ResponseInterface $response): ResponseContract
    {
        return new Response($response);
    }

    /**
     * Get API Header.
     *
     * @return array
     */
    protected function getApiHeaders(): array
    {
        $headers = [
            'Accept' => "application/vnd.insure.{$this->getVersion()}+json",
            'Content-Type' => "application/json",
        ];

        if (! \is_null($accessToken = $this->client->getAccessToken())) {
            $headers['Authorization'] = "Bearer {$accessToken}";
        }

        return $headers;
    }

    /**
     * Check for access token is available before trying to make a request.
     *
     * @return void
     */
    final protected function requiresAccessToken(): void
    {
        if (\is_null($accessToken = $this->client->getAccessToken())) {
            throw new Exceptions\MissingAccessToken('Request requires valid access token to be available!');
        }
    }

    /**
     * Build query string from Katsana\Sdk\Query.
     *
     * @param \Katsana\Insurance\Query $query
     *
     * @return array
     */
    final protected function buildHttpQuery(?Query $query): array
    {
        return $query instanceof Query ? $query->toArray() : [];
    }
}
