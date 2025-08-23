<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\CircuitBreaker\CircuitBreaker;
use App\Core\CircuitBreaker\Enums\CircuitBreakerState;
use App\Core\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use Exception;

    const FAILURE_THRESHOLD = 3;
    const TIMEOUT_DURATION = 60; // seconds
    const RETRY_TIMEOUT = 5; // seconds

    beforeEach(function () {
        $this->circuitBreaker = new CircuitBreaker(
            FAILURE_THRESHOLD,
            TIMEOUT_DURATION,
            RETRY_TIMEOUT
        );
    });

    it('starts in closed state', function () {
        expect($this->circuitBreaker->getState())->toBe(CircuitBreakerState::CLOSED->value)
            ->and($this->circuitBreaker->getFailureCount())->toBe(0);
    });

    it('executes successful calls', function () {
        $result = $this->circuitBreaker->call(function () {
            return 'success';
        });

        expect($result)->toBe('success')
            ->and($this->circuitBreaker->getState())->toBe(CircuitBreakerState::CLOSED->value)
            ->and($this->circuitBreaker->getFailureCount())->toBe(0);
    });

    it('should counts failures and opens circuit when threshold is reached', function () {
        for ($i = 0; $i < 3; $i++) {
            try {
                $this->circuitBreaker->call(function () {
                    throw new Exception('Service unavailable');
                });
            } catch (Exception $e) {
                //
            }
        }

        expect($this->circuitBreaker->getState())->toBe(CircuitBreakerState::OPEN->value)
            ->and($this->circuitBreaker->getFailureCount())->toBe(3);
    });

    it('throws CircuitBreakerOpenException when circuit is open', function () {
        $this->circuitBreaker->forceOpen();

        expect(function () {
            $this->circuitBreaker->call(function () {
                return 'should not execute';
            });
        })->toThrow(CircuitBreakerOpenException::class);
    });

    it('resets failure count on successful call', function () {
        try {
            $this->circuitBreaker->call(function () {
                throw new Exception('Failure');
            });
        } catch (Exception $e) {
            //
        }

        expect($this->circuitBreaker->getFailureCount())->toBe(1);

        $this->circuitBreaker->call(function () {
            return 'success';
        });

        expect($this->circuitBreaker->getFailureCount())->toBe(0);
    });

    it('can be manually reset', function () {
        $this->circuitBreaker->forceOpen();
        expect($this->circuitBreaker->getState())->toBe(CircuitBreakerState::OPEN->value);

        $this->circuitBreaker->reset();

        expect($this->circuitBreaker->getState())->toBe(CircuitBreakerState::CLOSED->value)
            ->and($this->circuitBreaker->getFailureCount())->toBe(0);
    });

    it('should provides circuit breaker statistics', function () {
        $stats = $this->circuitBreaker->getStats();

        expect($stats)->toHaveKey('state')
            ->and($stats)->toHaveKey('failure_count')
            ->and($stats)->toHaveKey('failure_threshold')
            ->and($stats)->toHaveKey('last_failure_time')
            ->and($stats)->toHaveKey('last_success_time')
            ->and($stats)->toHaveKey('timeout_duration')
            ->and($stats)->toHaveKey('retry_timeout');
    });

    it('should transit from open to half-open after retry timeout', function () {
        // Create circuit breaker with short retry timeout for testing
        $circuitBreaker = new CircuitBreaker(
            failureThreshold: 2,
            timeoutDuration: 60,
            retryTimeout: 1 // 1 second
        );

        for ($i = 0; $i < 2; $i++) {
            try {
                $circuitBreaker->call(function () {
                    throw new Exception('Service unavailable');
                });
            } catch (Exception $e) {
                //
            }
        }

        expect($circuitBreaker->getState())->toBe(CircuitBreakerState::OPEN->value);

        sleep(2);

        expect($circuitBreaker->getState())->toBe(CircuitBreakerState::HALF_OPEN->value);
    });

    it('transitions from half-open to closed on successful call', function () {
        $circuitBreaker = new CircuitBreaker(
            failureThreshold: 2,
            timeoutDuration: 60,
            retryTimeout: 1
        );

        // Open the circuit
        for ($i = 0; $i < 2; $i++) {
            try {
                $circuitBreaker->call(function () {
                    throw new Exception('Service unavailable');
                });
            } catch (Exception $e) {
                //
            }
        }

        sleep(2);
        expect($circuitBreaker->getState())->toBe(CircuitBreakerState::HALF_OPEN->value);

        $result = $circuitBreaker->call(function () {
            return 'success';
        });

        expect($result)->toBe('success')
            ->and($circuitBreaker->getState())->toBe(CircuitBreakerState::CLOSED->value);
    });
