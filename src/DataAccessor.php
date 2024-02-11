<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use BackedEnum;
use DateTimeImmutable;
use RuntimeException;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;
use ScrumWorks\EnumMapping\EnumMappingProvider;
use UnitEnum;
use ScrumWorks\EnumMapping\AbstractEnumMapping;

/**
 * @template TEnumMapping of AbstractEnumMapping
 * @template TUnitEnum of UnitEnum
 */
final readonly class DataAccessor
{
    /**
     * @param DataAccessorFactory<TEnumMapping, TUnitEnum> $dataAccessorFactory
     * @param EnumMappingProvider<TEnumMapping, TUnitEnum> $enumMappingProvider
     */
    public function __construct(
        private DataAccessorFactory $dataAccessorFactory,
        private EnumMappingProvider $enumMappingProvider,
        private mixed $data,
    ) {}

    public function getData(): mixed
    {
        return $this->data;
    }

    public function exists(string $attrPath): bool
    {
        return \array_key_exists($attrPath, $this->asArray());
    }

    /**
     * @return self<TEnumMapping, TUnitEnum>
     */
    public function getAttr(string $attrName): self
    {
        return $this->getOptionalAttr($attrName)
            ?? throw new RuntimeException("Attribute `{$attrName}` not found.");
    }

    /**
     * @return self<TEnumMapping, TUnitEnum>|null
     */
    public function getOptionalAttr(string $attrName): ?self
    {
        $data = $this->asArray();
        if (! \array_key_exists($attrName, $data)) {
            return null;
        }

        return $this->dataAccessorFactory->createFromData($data[$attrName]);
    }

    /**
     * @return array<mixed>
     */
    public function asArray(): array
    {
        if (! \is_array($this->data)) {
            throw $this->createTypeException('Array');
        }

        return $this->data;
    }

    public function asString(): string
    {
        if (! \is_string($this->data)) {
            throw $this->createTypeException('String');
        }

        return $this->data;
    }

    public function asNullableString(): ?string
    {
        if ($this->data === null) {
            return null;
        }

        return $this->asString();
    }

    public function asInt(): int
    {
        if (! \is_int($this->data)) {
            throw $this->createTypeException('Int');
        }

        return $this->data;
    }

    public function asNullableInt(): ?int
    {
        if ($this->data === null) {
            return null;
        }

        return $this->asInt();
    }

    public function asFloat(): float
    {
        if (! \is_float($this->data)) {
            throw $this->createTypeException('Float');
        }

        return $this->data;
    }

    public function asNullableFloat(): ?float
    {
        if ($this->data === null) {
            return null;
        }

        return $this->asFloat();
    }

    public function asBool(): bool
    {
        if (! \is_bool($this->data)) {
            throw $this->createTypeException('Bool');
        }

        return $this->data;
    }

    public function asNullableBool(): ?bool
    {
        if ($this->data === null) {
            return null;
        }

        return $this->asBool();
    }

    public function asDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->asString());
    }

    public function asNullableDateTime(): ?DateTimeImmutable
    {
        $value = $this->asNullableString();
        return $value === null ? null : new DateTimeImmutable($value);
    }

    public function asTimestamp(): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($this->asInt());
    }

    public function asNullableTimestamp(): ?DateTimeImmutable
    {
        $value = $this->asNullableInt();
        return $value === null ? null : (new DateTimeImmutable())->setTimestamp($value);
    }

    /**
     * @param class-string<TUnitEnum&BackedEnum> $enumClass
     *
     * @return TUnitEnum&BackedEnum
     */
    public function asEnum(string $enumClass): UnitEnum
    {
        $data = $this->asNullableEnum($enumClass);
        if (! ($data instanceof $enumClass)) {
            throw $this->createTypeException($enumClass);
        }

        return $data;
    }

    /**
     * @param class-string<TUnitEnum&BackedEnum> $enumClass
     *
     * @return (TUnitEnum&BackedEnum)|null
     */
    public function asNullableEnum(string $enumClass): ?UnitEnum
    {
        if ($this->data === null) {
            return null;
        }
        if (! \is_int($this->data) && ! \is_string($this->data)) {
            throw new DataAccessorException($this->data, 'Data has to be int or string');
        }

        return $enumClass::from($this->data);
    }

    /**
     * @param class-string<TEnumMapping> $enumMapping
     * @param class-string<TUnitEnum> $enumClass
     *
     * @return TUnitEnum
     */
    public function asMappedEnum(string $enumMapping, string $enumClass): UnitEnum
    {
        return $this->enumMappingProvider->get($enumMapping)->stringToEnum($enumClass, $this->asString());
    }

    private function createTypeException(string $type): RuntimeException
    {
        return new RuntimeException("{$type} expected. Got: " . \var_export($this->data, true));
    }
}
