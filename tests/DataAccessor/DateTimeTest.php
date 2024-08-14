<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class DateTimeTest extends AbstractDataAccessorTestCase
{
    public function testAsDateTimeOk(): void
    {
        self::assertSame(
            '2024-02-03 04:05:06',
            $this->factory->createFromData('2024-02-03 04:05:06')->asDateTime()->format('Y-m-d H:i:s'),
        );
    }

    #[DataProvider(methodName: 'dpTestAsDateTimeInvalidData')]
    public function testAsDateTimeInvalidData(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asDateTime();
    }

    public static function dpTestAsDateTimeInvalidData(): Iterator
    {
        yield ['invalid'];
        yield [null];
    }

    public function testAsNullableDateTimeOk(): void
    {
        self::assertSame(
            '2024-02-03 04:05:06',
            $this->factory->createFromData('2024-02-03 04:05:06')->asNullableDateTime()->format('Y-m-d H:i:s'),
        );
    }

    public function testAsNullableDateTimeNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableDateTime());
    }

    public function testAsNullableDateTimeInvalidData(): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData('invalid data')->asNullableDateTime();
    }
}
