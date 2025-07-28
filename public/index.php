<?php

declare(strict_types=1);

use App\Core\Validations\DocumentValidator;
use App\Core\Validations\Enums\DocumentValidatorEnum;

require __DIR__ . '/../vendor/autoload.php';

// @codeCoverageIgnoreStart

$validator = DocumentValidator::forType(DocumentValidatorEnum::CPF);

ds($validator->validate('12345678909'));

$validator = DocumentValidator::forType(DocumentValidatorEnum::CNPJ);
ds($validator->validate('12345678000195'));

// @codeCoverageIgnoreEnd
