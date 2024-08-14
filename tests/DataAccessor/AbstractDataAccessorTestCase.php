<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Tests\DataAccessor;

use PHPUnit\Framework\TestCase;
use ScrumWorks\DataAccessor\DataAccessorFactory;

abstract class AbstractDataAccessorTestCase extends TestCase
{
    protected DataAccessorFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new DataAccessorFactory();
    }
}
