<?php

namespace Katsana\Insurance\One;

use Katsana\Insurance\Query;
use Laravie\Codex\Contracts\Response;

class Insurer extends Request
{
    /**
     * Get all available insurer.
     *
     * @param \Katsana\Insurance\Query|null $query
     *
     * @return \Katsana\Insurance\Response
     */
    public function all(?Query $query = null): Response
    {
        $this->requiresAccessToken();

        return $this->send(
            'GET', 'insurers', $this->getApiHeaders(), $this->buildHttpQuery($query)
        );
    }
}
