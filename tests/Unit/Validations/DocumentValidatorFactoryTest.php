<?php

declare(strict_types=1);


namespace Tests\Unit\Validations;

use App\Core\Validations\Document\CnpjValidator;
use App\Core\Validations\Document\CpfValidator;
use App\Core\Validations\DocumentValidatorFactory;
use App\Core\Validations\Enums\DocumentValidatorEnum;
use InvalidArgumentException;

it('Should return a valid document validator strategy', function (DocumentValidatorEnum $strategy) {
    $validator = DocumentValidatorFactory::create($strategy->value);

    $expectedClass = match ($strategy) {
        DocumentValidatorEnum::CPF => CpfValidator::class,
        DocumentValidatorEnum::CNPJ => CnpjValidator::class,
    };

    expect($validator)->toBeInstanceOf($expectedClass);
})->with([
    'CPF' => [DocumentValidatorEnum::CPF],
    'CNPJ' => [DocumentValidatorEnum::CNPJ],
]);

it('Should except invalid types', function () {
    DocumentValidatorFactory::create('invalid-document-type');
})->throws(
    InvalidArgumentException::class,
    'No validator found for document type: invalid-document-type'
);