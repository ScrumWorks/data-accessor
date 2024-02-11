<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use ScrumWorks\DataAccessor\Exception\JsonDecodeException;
use ScrumWorks\EnumMapping\AbstractEnumMapping;
use ScrumWorks\EnumMapping\EnumMappingProvider;
use UnitEnum;

/**
 * @template TEnumMapping of AbstractEnumMapping
 * @template TUnitEnum of UnitEnum
 */
final readonly class DataAccessorFactory
{
    /**
     * @param EnumMappingProvider<TEnumMapping, TUnitEnum> $enumMappingProvider
     */
    public function __construct(private EnumMappingProvider $enumMappingProvider)
    {
    }

    /**
     * @return DataAccessor<TEnumMapping, TUnitEnum>
     * @throw JsonDecodeException
     */
    public function createFromData(mixed $data): DataAccessor
    {
        return new DataAccessor($this, $this->enumMappingProvider, $data);
    }

    /**
     * @return DataAccessor<TEnumMapping, TUnitEnum>
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
