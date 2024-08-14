<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class FloatTest extends AbstractDataAccessorTestCase
{
    public function testAsFloatOk(): void
    {
        self::assertSame(123.77, $this->factory->createFromData(123.77)->asFloat());
    }

    #[DataProvider(methodName: 'dpTestAsFloatInvalidType')]
    public function testAsFloatInvalidType(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asFloat();
    }

    public static function dpTestAsFloatInvalidType(): Iterator
    {
        yield ['123.5'];
        yield [null];
    }

    public function testAsNullableFloatOk(): void
    {
        self::assertSame(123.77, $this->factory->createFromData(123.77)->asNullableFloat());
    }

    public function testAsNullableFloatNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableFloat());
    }

    #[DataProvider(methodName: 'dpTestToFloatOk')]
    public function testToFloatOk(mixed $input, float $output): void
    {
        self::assertSame($output, $this->factory->createFromData($input)->toFloat());
    }

    public static function dpTestToFloatOk(): Iterator
    {
        yield ['123', 123.0];
        yield ['123.5', 123.5];
        yield ['-3', -3.0];
        yield ['-3.5', -3.5];
        yield [0.5, 0.5];
        yield [666, 666.0];
        yield [-33, -33.0];
    }

    #[DataProvider(methodName: 'dpTestToFloatInvalidType')]
    public function testToFloatInvalidType(mixed $input): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($input)->toFloat();
    }

    public static function dpTestToFloatInvalidType(): Iterator
    {
        yield [[123]];
        yield [(object) []];
        yield [null];
        yield [true];
        yield [false];
    }
}
