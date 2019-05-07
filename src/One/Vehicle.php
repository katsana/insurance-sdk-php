<?php

namespace Katsana\Insurance\One;

use Katsana\Insurance\Query;
use Laravie\Codex\Contracts\Response;

class Vehicle extends Request
{
    /**
     * Save a vehicle information.
     *
     * @param array  $payload  Array of vehicle and owner's data.
     *
     * @return \Katsana\Insurance\Response
     */
    public function save(array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->send(
            'POST', "vehicles?include=customer", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }

    /**
     * Pay for a quotation belonging to a specific vehicle.
     *
     * @param string  $plateNumber  Vehicle's plate number.
     * @param string  $insurerCode  Insurer's product code.
     *
     * @return \Katsana\Insurance\Response
     */
    public function save(string $plateNumber, string $insurerCode, array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->send(
            'POST', "quotations/{$plateNumber}/{$insurerCode}/pay", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
