<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class IntTest extends AbstractDataAccessorTestCase
{
    public function testAsIntOk(): void
    {
        self::assertSame(123, $this->factory->createFromData(123)->asInt());
    }

    #[DataProvider(methodName: 'dpTestAsIntInvalidType')]
    public function testAsIntInvalidType(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asInt();
    }

    public static function dpTestAsIntInvalidType(): Iterator
    {
        yield ['123'];
        yield [null];
    }

    public function testAsNullableIntOk(): void
    {
        self::assertSame(123, $this->factory->createFromData(123)->asNullableInt());
    }

    public function testAsNullableIntNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableInt());
    }

    #[DataProvider(methodName: 'dpTestToIntOk')]
    public function testToIntOk(mixed $input, int $output): void
    {
        self::assertSame($output, $this->factory->createFromData($input)->toInt());
    }

    public static function dpTestToIntOk(): Iterator
    {
        yield ['123', 123];
        yield ['123.5', 123];
        yield ['-3', -3];
        yield ['-3.5', -3];
        yield [0.5, 0];
        yield [56.9, 56];
        yield [666, 666];
        yield [false, 0];
        yield [true, 1];
    }

    #[DataProvider(methodName: 'dpTestToIntInvalidType')]
    public function testToIntInvalidType(mixed $input): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($input)->toInt();
    }

    public static function dpTestToIntInvalidType(): Iterator
    {
        yield [[123]];
        yield [(object) []];
        yield [null];
    }
}
