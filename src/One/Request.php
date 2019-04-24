<?php

namespace Katsana\Insurance\One;

use Katsana\Insurance\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v1';
}
