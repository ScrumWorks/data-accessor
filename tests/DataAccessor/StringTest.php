<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class StringTest extends AbstractDataAccessorTestCase
{
    public function testAsStringOk(): void
    {
        self::assertSame("abc", $this->factory->createFromData("abc")->asString());
    }

    #[DataProvider(methodName: 'dpTestAsStringInvalidType')]
    public function testAsStringInvalidType(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asString();
    }

    public static function dpTestAsStringInvalidType(): Iterator
    {
        yield [123];
        yield [true];
        yield [5.9];
        yield [[1]];
        yield [null];
    }

    public function testAsNullableStringOk(): void
    {
        self::assertSame("abc", $this->factory->createFromData("abc")->asNullableString());
    }

    public function testAsNullableStringNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableString());
    }

    #[DataProvider(methodName: 'dpTestToStringOk')]
    public function testToStringOk(mixed $input, string $output): void
    {
        self::assertSame($output, $this->factory->createFromData($input)->toString());
    }

    public static function dpTestToStringOk(): Iterator
    {
        yield [123, '123'];
        yield [123.45, '123.45'];
        yield ['abc', 'abc'];
    }

    #[DataProvider(methodName: 'dpTestToStringInvalidType')]
    public function testToStringInvalidType(mixed $input): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($input)->toString();
    }

    public static function dpTestToStringInvalidType(): Iterator
    {
        yield [false];
        yield [[123]];
        yield [(object) []];
        yield [null];
    }
}
