<?php

namespace Katsana\Insurance\One;

use Laravie\Codex\Contracts\Response;

class Quotation extends Request
{
    /**
     * Draft a new quotation.
     *
     * @param string     $plateNumber        vehicle's plate number
     * @param string     $insurerCode        insurer's product code
     * @param array      $ownerInformation   vehicle owner's information
     * @param array      $vehicleInformation vehicle's information
     * @param float|null $sumCovered         amount of covered sum
     * @param array      $addons             insurance policy addons
     *
     * @return \Katsana\Insurance\Response
     */
    public function draft(
        string $plateNumber,
        string $insurerCode,
        array $ownerInformation,
        array $vehicleInformation = [],
        $sumCovered = null,
        array $addons = []
    ): Response {
        $this->requiresAccessToken();

        $vehicleInformation['plate_number'] = $plateNumber;

        $payload = [
            'owner' => $ownerInformation,
            'vehicle' => $vehicleInformation,
            'sum_covered' => $sumCovered,
            'addons' => $addons,
        ];

        return $this->sendJson(
            'POST', "quotations/{$insurerCode}", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }

    /**
     * Update quotation belonging to a specific vehicle.
     *
     * @param string $plateNumber vehicle's plate number
     * @param string $insurerCode insurer's product code
     * @param float  $sumCovered  amount of covered sum
     * @param array  $addons      insurance policy addons
     *
     * @return \Katsana\Insurance\Response
     */
    public function update(
        string $plateNumber,
        string $insurerCode,
        $sumCovered,
        array $addons = []
    ): Response {
        $this->requiresAccessToken();

        $payload = [
            'sum_covered' => $sumCovered,
            'addons' => $addons,
        ];

        return $this->sendJson(
            'PATCH', "quotations/{$plateNumber}/{$insurerCode}", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
