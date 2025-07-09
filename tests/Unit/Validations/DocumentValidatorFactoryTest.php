<?php

declare(strict_types=1);


namespace Tests\Unit\Validations;

use App\Core\Validations\Document\CnpjValidator;
use App\Core\Validations\Document\CpfValidator;
use App\Core\Validations\DocumentValidatorFactory;
use App\Core\Validations\Enums\DocumentValidatorEnum;
use InvalidArgumentException;

it('Should return a valid document validator strategy', function (DocumentValidatorEnum $strategy, string $instance) {
    $validator = DocumentValidatorFactory::create($strategy->value);

    expect($validator)->toBeInstanceOf($instance);
})->with([
    'CPF' => [
        'strategy' => DocumentValidatorEnum::CPF,
        'instance' => CpfValidator::class
    ],
    'CNPJ' => [
        'strategy' => DocumentValidatorEnum::CNPJ,
        'instance' => CnpjValidator::class
    ]
]);

it('Should except invalid types', function () {
    $validator = DocumentValidatorFactory::create('invalid-document-type');
})->throws(
    InvalidArgumentException::class,
    'No validator found for document type: invalid-document-type'
);