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
     * @param int $sumCovered amount of covered sum
     * @param array $payload
     *
     * @return \Katsana\Insurance\Response
     */
    public function pay(
        string $plateNumber,
        string $insurerCode,
        $sumCovered,
        array $addons = [],
        array $declarations = ['pds' => false, 'ind' => false, 'pdpa' => false, 'lapse' => false]
    ): Response {
        $this->requiresAccessToken();

        $payload = [
            'sum_covered' => $sumCovered,
            'addons' => $addons,
            'declarations' => \array_merge([
                'pds' => false, 'ind' => false, 'pdpa' => false, 'lapse' => false,
            ], $declarations)
        ];

        return $this->sendJson(
            'POST', "quotations/{$plateNumber}/{$insurerCode}/pay", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
