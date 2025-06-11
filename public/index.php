<?php

declare(strict_types=1);

use App\Core\Validations\Document\DocumentValidator;
use App\Core\Validations\Enums\DocumentValidatorEnum;

require __DIR__ . '/../vendor/autoload.php';

// @codeCoverageIgnoreStart

$validator = new DocumentValidator(DocumentValidatorEnum::CPF);

ds($validator->validate('12345678909'));

$validator = new DocumentValidator(DocumentValidatorEnum::CNPJ);
ds($validator->validate('12345678000195'));

// @codeCoverageIgnoreEnd
