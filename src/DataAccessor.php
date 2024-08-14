<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final readonly class DataAccessor
{
    public function __construct(
        private DataAccessorFactory $dataAccessorFactory,
        private DataConvertor $dataConvertor,
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

    public function getAttr(string $attrName): self
    {
        return $this->getOptionalAttr($attrName)
            ?? throw new DataAccessorException($this->data, "Attribute `{$attrName}` not found.");
    }

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

    public function toInt(): int
    {
        return $this->dataConvertor->toInt($this->data);
    }

    public function toNullableInt(): ?int
    {
        return $this->data === null ? null : $this->toInt();
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

    public function toFloat(): float
    {
        return $this->dataConvertor->toFloat($this->data);
    }

    public function toNullableFloat(): ?float
    {
        return $this->data === null ? null : $this->toFloat();
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

    public function toBool(): bool
    {
        return $this->dataConvertor->toBool($this->data);
    }

    public function toNullableBool(): ?bool
    {
        return $this->data === null ? null : $this->toBool();
    }

    public function asDateTime(): DateTimeInterface
    {
        try {
            return new DateTimeImmutable($this->asString());
        } catch (\Throwable $err) {
            throw new DataAccessorException($this->data, $err->getMessage(), previous: $err);
        }
    }

    public function asNullableDateTime(): ?DateTimeInterface
    {
        return $this->data === null ? null : $this->asDateTime();
    }

    public function asTimestamp(): DateTimeInterface
    {
        return (new DateTimeImmutable())->setTimestamp($this->asInt());
    }

    public function asNullableTimestamp(): ?DateTimeInterface
    {
        return $this->data === null ? null : $this->asTimestamp();
    }

    /**
     * @template T of BackedEnum
     * @param class-string<T> $enumClass
     * @return T
     */
    public function toEnum(string $enumClass): BackedEnum
    {
        return $this->dataConvertor->toEnum($this->data, $enumClass);
    }

    /**
     * @template T of BackedEnum
     * @param class-string<T> $enumClass
     *
     * @return T|null
     */
    public function toNullableEnum(string $enumClass): ?BackedEnum
    {
        return $this->data === null ? null : $this->toEnum($enumClass);
    }

    private function createTypeException(string $type): DataAccessorException
    {
        return new DataAccessorException($this->data, "{$type} expected. Got: " . \var_export($this->data, true));
    }
}
