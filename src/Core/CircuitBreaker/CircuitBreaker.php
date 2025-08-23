<?php

declare(strict_types=1);

namespace App\Core\CircuitBreaker;

use App\Core\CircuitBreaker\Contracts\CircuitBreakerInterface;
use App\Core\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use App\Core\CircuitBreaker\Enums\CircuitBreakerState;
use Exception;

class CircuitBreaker implements CircuitBreakerInterface
{
    private CircuitBreakerState $state;
    private int $failureCount;
    private ?int $lastFailureTime;
    private ?int $lastSuccessTime;

    public function __construct(
        private readonly int $failureThreshold = 5,
        private readonly int $timeoutDuration = 60,
        private readonly int $retryTimeout = 30
    ) {
        $this->state = CircuitBreakerState::CLOSED;
        $this->failureCount = 0;
        $this->lastFailureTime = null;
        $this->lastSuccessTime = null;
    }

    public function call(callable $callable): mixed
    {
        $this->updateStateBasedOnTime();

        if ($this->state === CircuitBreakerState::OPEN) {
            throw new CircuitBreakerOpenException(
                'Circuit breaker is open. Service temporarily unavailable.'
            );
        }

        try {
            $result = $callable();
            $this->onSuccess();
            return $result;
        } catch (Exception $e) {
            $this->onFailure();
            throw $e;
        }
    }

    public function getState(): string
    {
        $this->updateStateBasedOnTime();
        return $this->state->value;
    }

    public function reset(): void
    {
        $this->state = CircuitBreakerState::CLOSED;
        $this->failureCount = 0;
        $this->lastFailureTime = null;
        $this->lastSuccessTime = time();
    }

    public function forceOpen(): void
    {
        $this->state = CircuitBreakerState::OPEN;
        $this->lastFailureTime = time();
    }

    public function getFailureCount(): int
    {
        return $this->failureCount;
    }

    public function getLastFailureTime(): ?int
    {
        return $this->lastFailureTime;
    }

    private function onSuccess(): void
    {
        $this->failureCount = 0;
        $this->lastSuccessTime = time();

        if ($this->state === CircuitBreakerState::HALF_OPEN) {
            $this->state = CircuitBreakerState::CLOSED;
        }
    }

    private function onFailure(): void
    {
        $this->failureCount++;
        $this->lastFailureTime = time();

        if ($this->failureCount >= $this->failureThreshold) {
            $this->state = CircuitBreakerState::OPEN;
        }
    }

    private function updateStateBasedOnTime(): void
    {
        if ($this->state === CircuitBreakerState::OPEN && $this->shouldAttemptReset()) {
            $this->state = CircuitBreakerState::HALF_OPEN;
        }
    }

    private function shouldAttemptReset(): bool
    {
        if ($this->lastFailureTime === null) {
            return false;
        }

        return (time() - $this->lastFailureTime) >= $this->retryTimeout;
    }

    public function getStats(): array
    {
        return [
            'state' => $this->getState(),
            'failure_count' => $this->failureCount,
            'failure_threshold' => $this->failureThreshold,
            'last_failure_time' => $this->lastFailureTime,
            'last_success_time' => $this->lastSuccessTime,
            'timeout_duration' => $this->timeoutDuration,
            'retry_timeout' => $this->retryTimeout,
        ];
    }
}
