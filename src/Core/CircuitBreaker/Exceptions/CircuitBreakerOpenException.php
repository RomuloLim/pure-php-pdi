<?php

declare(strict_types=1);

namespace App\Core\CircuitBreaker\Exceptions;

use Exception;

class CircuitBreakerOpenException extends Exception
{
    public function __construct(string $message = 'Circuit breaker is open', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
