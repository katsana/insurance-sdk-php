<?php

namespace Katsana\Insurance\One;

use Laravie\Codex\Contracts\Response;

class Vehicle extends Request
{
    /**
     * Save a vehicle information.
     *
     * @param array $payload array of vehicle and owner's data
     *
     * @return \Katsana\Insurance\Response
     */
    public function save(array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->sendJson(
            'POST', 'vehicles?include=customer', $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }

    /**
     * Pay for a quotation belonging to a specific vehicle.
     *
     * @param string $plateNumber vehicle's plate number
     * @param string $insurerCode insurer's product code
     *
     * @return \Katsana\Insurance\Response
     */
    public function pay(string $plateNumber, string $insurerCode, array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->sendJson(
            'POST', "quotations/{$plateNumber}/{$insurerCode}/pay", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
