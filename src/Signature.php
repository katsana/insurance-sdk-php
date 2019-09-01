<?php

namespace Katsana\Insurance;

use Laravie\Codex\Security\Signature\Verify;
use Psr\Http\Message\ResponseInterface;

class Signature
{
    /**
     * Signature key.
     *
     * @var string
     */
    protected $key;

    /**
     * Construct a new signature.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Verify signature from header and body.
     *
     * @param string $header
     * @param string $body
     *
     * @return bool
     */
    final public function verify(string $header, string $body): bool
    {
        $signature = new Verify($this->key, 'sha256');

        return $signature($body, $header);
    }

    /**
     * Verify signature from PSR7 response object.
     *
     * @param \Psr\Http\Message\ResponseInterface $message
     *
     * @return bool
     */
    final public function verifyFrom(ResponseInterface $message): bool
    {
        $response = new Response($message);

        return $this->verify(
            $response->getHeader('X-Insurance-Signature')[0],
            $response->getBody()
        );
    }
}
