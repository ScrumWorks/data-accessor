<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use ScrumWorks\DataAccessor\Exception\JsonDecodeException;

final readonly class DataAccessorFactory
{
    /**
     * @throw JsonDecodeException
     */
    public function createFromData(mixed $data): DataAccessor
    {
        return new DataAccessor($this, $data);
    }

    /**
     * @throw JsonDecodeException
     */
    public function createFromJson(string $json): DataAccessor
    {
        $decodedJson = \json_decode($json, true);
        if ($decodedJson === null) {
            throw new JsonDecodeException($json, 'Unable to decode provided json.');
        }

        return $this->createFromData($decodedJson);
    }
}
