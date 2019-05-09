<?php

namespace Katsana\Insurance\One;

use Laravie\Codex\Concerns\Request\Json;
use Katsana\Insurance\Request as BaseRequest;

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
