<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\CircuitBreaker\CircuitBreaker;

echo "=== Exemplo Básico do Circuit Breaker ===\n\n";

$circuitBreaker = new CircuitBreaker(
    failureThreshold: 3,
    timeoutDuration: 60,
    retryTimeout: 5
);

$externalService = function (bool $shouldFail = false) {
    if ($shouldFail) {
        throw new Exception('Serviço indisponível no momento (ou fallback)');
    }
    return 'Dados do serviço externo';
};

//------------------------------

echo "1. Testando chamadas bem-sucedidas:\n";
for ($i = 1; $i <= 3; $i++) {
    try {
        $result = $circuitBreaker->call(fn() => $externalService(false));
        echo "  Chamada $i: ✅ $result\n";
    } catch (Exception $e) {
        echo "  Chamada $i: ❌ {$e->getMessage()}\n";
    }
}

echo "\nStatus do circuito: {$circuitBreaker->getState()}\n";
echo "Falhas: {$circuitBreaker->getFailureCount()}\n\n";

//------------------------------

echo "2. Forçando falhas para abrir o circuito:\n";
for ($i = 1; $i <= 4; $i++) {
    try {
        $result = $circuitBreaker->call(fn() => $externalService(true));
        echo "  Chamada $i: ✅ $result\n";
    } catch (Exception $e) {
        echo "  Chamada $i: ❌ {$e->getMessage()}\n";
    }
}

echo "\nStatus do circuito: {$circuitBreaker->getState()}\n";
echo "Falhas: {$circuitBreaker->getFailureCount()}\n\n";

//------------------------------
echo "3. Tentando chamadas com circuito aberto:\n";
for ($i = 1; $i <= 2; $i++) {
    try {
        $result = $circuitBreaker->call(fn() => $externalService(false));
        echo "  Chamada $i: ✅ $result\n";
    } catch (Exception $e) {
        echo "  Chamada $i: ❌ {$e->getMessage()}\n";
    }
}

//------------------------------

echo "4. Aguardando para tentar novamente após timeout...\n";
echo "\nStatus do circuito: {$circuitBreaker->getState()}\n"; // open

sleep(6); // mais que o tempo de espera

echo "\nStatus do circuito: {$circuitBreaker->getState()}\n"; // half-open

try {
    $result = $circuitBreaker->call(fn() => $externalService(false));
    echo "  Chamada após timeout: ✅ $result\n";
} catch (Exception $e) {
    echo "  Chamada após timeout: ❌ {$e->getMessage()}\n";
}
