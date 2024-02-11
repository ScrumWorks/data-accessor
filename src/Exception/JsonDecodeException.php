<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Exception;

use Throwable;

final class JsonDecodeException extends DataAccessorException
{
    public function __construct(
        string $data,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($data, $message, $code, $previous);
    }
}
