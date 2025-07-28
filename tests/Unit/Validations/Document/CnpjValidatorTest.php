<?php

declare(strict_types=1);

use App\Core\Validations\Contracts\DocumentValidatorStrategy;
use App\Core\Validations\Document\CnpjValidator;

it('Should validate a valid CNPJ', function ($cnpj) {
    $validator = new CnpjValidator();

    expect($validator->isValid($cnpj))->toBeTrue();
})->with([
    'with dot mask' => '12.345.678/0001-95',
    'without mask' => '12345678000195'
]);

it('Should invalidate an invalid CNPJ', function ($cnpj) {
    $validator = new CnpjValidator();

    expect($validator->isValid($cnpj))->toBeFalse();
})->with([
    'with dots mask' => '12.345.678/0001-00',
    'without mask' => '12345678000100',
    'with invalid length' => '1234567800019',
    'with the same numbers' => '00000000000000',
    'with letters' => 'invalid-cnpj',
    'empty' => '',
]);

it('Should implements DocumentValidatorStrategy interface', function () {
    $validator = new CnpjValidator();

    expect($validator)->toBeInstanceOf(DocumentValidatorStrategy::class);
});