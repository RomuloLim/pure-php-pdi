<?php

declare(strict_types=1);

namespace App\Core\CircuitBreaker\Enums;

enum CircuitBreakerState: string
{
    case CLOSED = 'closed';
    case OPEN = 'open';
    case HALF_OPEN = 'half_open';
}
