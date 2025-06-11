<?php

declare(strict_types=1);

namespace App\Core\Validations\Enums;

use App\Core\Validations\Document\CnpjValidator;
use App\Core\Validations\Document\CpfValidator;

enum DocumentValidatorEnum: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';

    public function getValidator(): string
    {
        return match ($this) {
            self::CPF => CpfValidator::class,
            self::CNPJ => CnpjValidator::class,
        };
    }
}
