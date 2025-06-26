<?php

declare(strict_types=1);

use App\Core\Validations\Document\CnpjValidator;

it('Should validate a valid CNPJ', function ($cnpj) {
    $validator = new CnpjValidator();

    expect($validator->isValid($cnpj))->toBeTrue();
})->with([
    '12.345.678/0001-95',
    '12345678000195'
]);

it('Should invalidate an invalid CNPJ', function ($cnpj) {
    $validator = new CnpjValidator();

    expect($validator->isValid($cnpj))->toBeFalse();
})->with([
    '12.345.678/0001-00',
    '12345678000100',
    '11111111000191',
    '00000000000000',
    'invalid-cnpj',
    '',
]);