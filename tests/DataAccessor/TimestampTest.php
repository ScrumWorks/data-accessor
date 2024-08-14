<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class TimestampTest extends AbstractDataAccessorTestCase
{
    public function testAsTimestampOk(): void
    {
        $now = \time();
        self::assertSame($now, $this->factory->createFromData($now)->asTimestamp()->getTimestamp());
    }

    #[DataProvider(methodName: 'dpTestAsTimestampInvalidData')]
    public function testAsTimestampInvalidData(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asTimestamp();
    }

    public static function dpTestAsTimestampInvalidData(): Iterator
    {
        yield ['invalid'];
        yield ['2020-01-01 00:00:00'];
        yield [null];
    }

    public function testAsNullableTimestampOk(): void
    {
        $now = \time();
        self::assertSame($now, $this->factory->createFromData($now)->asNullableTimestamp()->getTimestamp());
    }

    public function testAsNullableTimestampNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableTimestamp());
    }

    public function testAsNullableTimestampInvalidData(): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData('invalid data')->asNullableTimestamp();
    }
}
