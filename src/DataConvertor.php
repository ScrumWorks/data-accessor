<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor;

use BackedEnum;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

class DataConvertor
{
    public function toInt(mixed $data): int
    {
        \is_numeric($data) || \is_bool($data)
            || throw $this->createException($data, 'Value cannot be converted to int.');

        return (int) $data;
    }

    public function toFloat(mixed $data): float
    {
        \is_numeric($data) || throw $this->createException($data, 'Value cannot be converted to float.');

        return (float) $data;
    }

    public function toBool(mixed $data): bool
    {
        return match ($data) {
            false, 0, '0' => false,
            true, 1, '1' => true,
            default => throw $this->createException($data, 'Value cannot be converted to bool.'),
        };
    }

    /**
     * @template T of BackedEnum
     * @param class-string<T> $enumClass
     * @return T
     */
    public function toEnum(mixed $data, string $enumClass): BackedEnum
    {
        if (! \is_int($data) && ! \is_string($data)) {
            throw new DataAccessorException($data, 'Data has to be int or string');
        }

        try {
            return $enumClass::from($data);
        } catch (\Throwable $error) {
            throw new DataAccessorException($data, $error->getMessage(), previous: $error);
        }
    }

    protected function createException(mixed $data, string $message): DataAccessorException
    {
        return new DataAccessorException($data, $message . ' Given: ' . \var_export($data, true));
    }
}
