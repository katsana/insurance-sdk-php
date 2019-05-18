<?php

namespace Katsana\Insurance\One;

use Laravie\Codex\Contracts\Response;

class Renewal extends Request
{
    /**
     * Pay for a quotation belonging to a specific vehicle.
     *
     * @param string $plateNumber  vehicle's plate number
     * @param string $insurerCode  insurer's product code
     * @param float  $sumCovered   amount of covered sum
     * @param array  $addons       insurance policy addons
     * @param array  $declarations insurance policy declaration of agreements
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
            ], $declarations),
        ];

        return $this->sendJson(
            'POST', "quotations/{$plateNumber}/{$insurerCode}/pay", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
