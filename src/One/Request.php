<?php

namespace Katsana\Insurance\One;

use Katsana\Insurance\Request as BaseRequest;
use Laravie\Codex\Concerns\Request\Json;

abstract class Request extends BaseRequest
{
    use Json;

    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v1';
}
