<?php

namespace Katsana\Insurance\One;

use Laravie\Codex\Contracts\Response;

class Vehicle extends Request
{
    /**
     * Save a vehicle information.
     *
     * @param string $plateNumber          vehicle's plate number
     * @param array  $vehicleInformation   vehicle's information
     * @param array  $ownerInformation     vehicle owner's information
     * @param array  $insuranceInformation vehicle insurance's information
     *
     * @return \Katsana\Insurance\Response
     */
    public function save(
        string $plateNumber,
        array $vehicleInformation,
        array $ownerInformation,
        array $insuranceInformation
    ): Response {
        $this->requiresAccessToken();

        $payload = array_merge([
            'plate_number' => $plateNumber,
            'owner' => $ownerInformation,
            'insurance' => $insuranceInformation,
        ], $vehicleInformation);

        return $this->sendJson(
            'POST', 'vehicles?include=customer', $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
