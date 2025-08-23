<?php

declare(strict_types=1);

namespace App\Core\CircuitBreaker\Contracts;

use App\Core\CircuitBreaker\Exceptions\CircuitBreakerOpenException;

interface CircuitBreakerInterface
{
    /**
     * Execute a callable with circuit breaker protection
     *
     * @param callable $callable The function to execute
     * @return mixed The result of the callable execution
     * @throws CircuitBreakerOpenException When circuit is open
     */
    public function call(callable $callable): mixed;

    /**
     * Get current circuit state
     */
    public function getState(): string;

    /**
     * Reset circuit breaker to closed state
     */
    public function reset(): void;

    /**
     * Force circuit breaker to open state
     */
    public function forceOpen(): void;

    /**
     * Get failure count
     */
    public function getFailureCount(): int;
}
