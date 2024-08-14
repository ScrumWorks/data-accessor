<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

final class BoolTest extends AbstractDataAccessorTestCase
{
    public function testAsBoolOk(): void
    {
        self::assertSame(true, $this->factory->createFromData(true)->asBool());
    }

    #[DataProvider(methodName: 'dpTestAsBoolInvalidType')]
    public function testAsBoolInvalidType(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->asBool();
    }

    public static function dpTestAsBoolInvalidType(): Iterator
    {
        yield ['true'];
        yield [null];
    }

    public function testAsNullableBoolOk(): void
    {
        self::assertSame(true, $this->factory->createFromData(true)->asNullableBool());
    }

    public function testAsNullableBoolNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->asNullableBool());
    }

    #[DataProvider(methodName: 'dpTestToBoolOk')]
    public function testToBoolOk(mixed $input, bool $output): void
    {
        self::assertSame($output, $this->factory->createFromData($input)->toBool());
    }

    public static function dpTestToBoolOk(): Iterator
    {
        yield ['1', true];
        yield [1, true];
        yield ['0', false];
        yield [0, false];
        yield [false, false];
        yield [true, true];
    }

    #[DataProvider(methodName: 'dpTestToBoolInvalidType')]
    public function testToBoolInvalidType(mixed $input): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($input)->toBool();
    }

    public static function dpTestToBoolInvalidType(): Iterator
    {
        yield [[123]];
        yield [(object) []];
        yield [null];
        yield ['hello'];
        yield [123];
        yield [3.2];
    }
}
