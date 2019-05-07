<?php

namespace Katsana\Insurance\One;

use Katsana\Insurance\Query;
use Laravie\Codex\Contracts\Response;

class Quotation extends Request
{
    /**
     * Draft a new quotation.
     *
     * @param string  $plateNumber  Vehicle's plate number.
     * @param string  $insurerCode  Insurer's product code.
     * @param array   $payload      Array of vehicle and owner's data.
     *
     * @return \Katsana\Insurance\Response
     */
    public function draft(string $plateNumber, string $insurerCode, array $payload): Response
    {
        $this->requiresAccessToken();

        $payload = array_merge_recursive($payload, ['vehicle' => ['plate_number' => $plateNumber]]);

        return $this->send(
            'POST', "quotations/{$insurerCode}", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }

    /**
     * Update quotation belonging to a specific vehicle.
     *
     * @param string  $plateNumber  Vehicle's plate number.
     * @param string  $insurerCode  Insurer's product code.
     * @param array   $payload      Array of values for quotation.
     *
     * @return \Katsana\Insurance\Response
     */
    public function update(string $plateNumber, string $insurerCode, array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->send(
            'PATCH', "quotations/{$plateNumber}/{$insurerCode}", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }

    /**
     * Pay for a quotation belonging to a specific vehicle.
     *
     * @param string  $plateNumber  Vehicle's plate number.
     * @param string  $insurerCode  Insurer's product code.
     * @param array   $payload      Array of values for quotation.
     *
     * @return \Katsana\Insurance\Response
     */
    public function pay(string $plateNumber, string $insurerCode, array $payload): Response
    {
        $this->requiresAccessToken();

        return $this->send(
            'POST', "quotations/{$plateNumber}/{$insurerCode}/pay", $this->getApiHeaders(), $this->mergeApiBody($payload)
        );
    }
}
