<?php

namespace Katsana\Insurance\One;

class Renewal extends Request
{
    /**
     * Pay for a quotation belonging to a specific vehicle.
     *
     * @param string $plateNumber vehicle's plate number
     * @param string $insurerCode insurer's product code
     * @param int    $sumCovered  amount of covered sum
     * @param array  $payload
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
