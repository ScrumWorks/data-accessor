<?php

declare(strict_types=1);

namespace ScrumWorks\DataAccessor\Exception;

use RuntimeException;
use Throwable;

class DataAccessorException extends RuntimeException
{
    public function __construct(
        private readonly mixed $data,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
