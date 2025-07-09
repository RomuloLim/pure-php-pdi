<?php

declare(strict_types=1);


namespace App\Core\Validations;

use App\Core\Validations\Contracts\DocumentValidatorStrategy;
use App\Core\Validations\Document\CnpjValidator;
use App\Core\Validations\Document\CpfValidator;
use App\Core\Validations\Enums\DocumentValidatorEnum;
use InvalidArgumentException;

class DocumentValidatorFactory
{
    public static function create(string $documentType): DocumentValidatorStrategy
    {
        return match ($documentType) {
            DocumentValidatorEnum::CPF->value => new CpfValidator(),
            DocumentValidatorEnum::CNPJ->value => new CnpjValidator(),
            default => throw new InvalidArgumentException("No validator found for document type: {$documentType}"),
        };
    }
}