<?php

declare(strict_types=1);

use App\Core\Validations\Document\CpfValidator;

it('Should validate a valid CPF', function (string $cpf) {
    $validator = new CpfValidator();

    expect($validator->isValid($cpf))->toBeTrue();
})->with([
    '123.456.789-09',
    '12345678909'
]);

it('Should invalidate an invalid CPF', function (string $cpf) {
    $validator = new CpfValidator();

    expect($validator->isValid($cpf))->toBeFalse();
})->with([
    '123.456.789-00',
    '12345678900',
    '111111111111',
    '000.000.000-00',
    'invalid-cpf',
    '',
]);