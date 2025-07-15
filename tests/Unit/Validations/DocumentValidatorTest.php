<?php

declare(strict_types=1);

namespace Tests\Unit\Validations;

use App\Core\Validations\DocumentValidator;
use App\Core\Validations\Enums\DocumentValidatorEnum;

it('Should return a valid document validator strategy', function (DocumentValidatorEnum $documentType, string $document, bool $expected) {
    $validator = DocumentValidator::forType($documentType);
    $result = $validator->validate($document);

    expect($result)->toBe($expected);
})->with([
    'Valid CPF' => [
        'documentType' => DocumentValidatorEnum::CPF,
        'document' => '123.456.789-09',
        'expected' => true
    ],
    'Invalid CPF' => [
        'documentType' => DocumentValidatorEnum::CPF,
        'document' => '123.456.789-00',
        'expected' => false
    ],
    'Valid CNPJ' => [
        'documentType' => DocumentValidatorEnum::CNPJ,
        'document' => '12.345.678/0001-95',
        'expected' => true
    ],
    'Invalid CNPJ' => [
        'documentType' => DocumentValidatorEnum::CNPJ,
        'document' => '12.345.678/0001-00',
        'expected' => false
    ],
    'Invalid Document Type' => [
        'documentType' => DocumentValidatorEnum::CNPJ,
        'document' => 'invalid-cnpj',
        'expected' => false
    ]
]);
