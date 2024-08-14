<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use ScrumWorks\DataAccessor\Exception\DataAccessorException;

enum EnumFixture: string {
    case A = 'A';
    case B = 'B';
}

final class EnumTest extends AbstractDataAccessorTestCase
{
    public function testToEnumOk(): void
    {
        self::assertSame(EnumFixture::A, $this->factory->createFromData('A')->toEnum(EnumFixture::class));
    }

    #[DataProvider(methodName: 'dpTestToEnumInvalidType')]
    public function testToEnumInvalidType(mixed $data): void
    {
        $this->expectException(DataAccessorException::class);
        $this->factory->createFromData($data)->toEnum(EnumFixture::class);
    }

    public static function dpTestToEnumInvalidType(): Iterator
    {
        yield ['123'];
        yield [null];
    }

    public function testToNullableEnumOk(): void
    {
        self::assertSame(EnumFixture::A, $this->factory->createFromData('A')->toNullableEnum(EnumFixture::class));
    }

    public function testToNullableEnumNull(): void
    {
        self::assertNull($this->factory->createFromData(null)->toNullableEnum(EnumFixture::class));
    }
}
