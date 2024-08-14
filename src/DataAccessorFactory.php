<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use ScrumWorks\DataAccessor\Exception\JsonDecodeException;

final class DataAccessorFactory
{
    private DataConvertor $dataConvertor;

    public function __construct(
        ?DataConvertor $dataConvertor = null,
    ) {
        $this->dataConvertor = $dataConvertor ?? new DataConvertor();
    }

    /**
     * @throw JsonDecodeException
     */
    public function createFromData(mixed $data): DataAccessor
    {
        return new DataAccessor(
            dataAccessorFactory: $this,
            dataConvertor: $this->dataConvertor,
            data: $data,
        );
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
