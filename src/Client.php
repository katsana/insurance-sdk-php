<?php

namespace Katsana\Insurance;

use Http\Client\Common\HttpMethodsClient as HttpClient;
use Katsana\Insurance\Passport\ClientCredentialsGrant;
use Laravie\Codex\Concerns\Passport;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Laravie\Codex\Discovery;

class Client extends \Laravie\Codex\Client
{
    use Passport;

    /**
     * API endpoint.
     *
     * @var string
     */
    protected $apiEndpoint = 'https://api.insure.katsana.com';

    /**
     * Default version.
     *
     * @var string
     */
    protected $defaultVersion = 'v1';

    /**
     * List of supported API versions.
     *
     * @var array
     */
    protected $supportedVersions = [
        'v1' => 'One',
    ];

    /**
     * Construct a new Client.
     *
     * @param \Http\Client\Common\HttpMethodsClient $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * Make a client.
     *
     * @param string|null $clientId
     * @param string|null $clientSecret
     *
     * @return static
     */
    public static function make(?string $clientId, ?string $clientSecret)
    {
        return (new static(Discovery::client()))
                        ->setClientId($clientId)
                        ->setClientSecret($clientSecret);
    }

    /**
     * Make a client using access token.
     *
     * @param string $accessToken
     *
     * @return static
     */
    public static function fromAccessToken(string $accessToken)
    {
        return static::make(null, null)->setAccessToken($accessToken);
    }

    /**
     * Use sandbox environment.
     *
     * @return $this
     */
    final public function useSandbox(): self
    {
        return $this->useCustomApiEndpoint('https://api.insure-staging.katsanalabs.com');
    }

    /**
     * Authenticate helper using OAuth2.
     *
     * @param string $scope
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    final public function authenticate(string $scope = '*'): ResponseContract
    {
        return $this->via(new ClientCredentialsGrant())->authenticate();
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    protected function getResourceNamespace(): string
    {
        return __NAMESPACE__;
    }
}
