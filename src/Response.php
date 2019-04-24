<?php

namespace Katsana\Insurance;

class Response extends \Laravie\Codex\Response
{
    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate()
    {
        $this->abortIfRequestNotFound();
        $this->abortIfRequestUnauthorized();

        return $this;
    }
}
