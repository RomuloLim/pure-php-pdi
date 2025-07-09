<?php

declare(strict_types=1);

use App\Core\Validations\Document\CpfValidator;

it('Should validate a valid CPF', function (string $cpf) {
    $validator = new CpfValidator();

    expect($validator->isValid($cpf))->toBeTrue();
})->with([
    'with dot mask' => '123.456.789-09',
    'without mask' => '12345678909'
]);

it('Should invalidate an invalid CPF', function (string $cpf) {
    $validator = new CpfValidator();

    expect($validator->isValid($cpf))->toBeFalse();
})->with([
    'with dot mask' => '123.456.789-00',
    'without mask' => '12345678900',
    'with same numbers' => '111111111111',
    'with same numbers using dot mask' => '000.000.000-00',
    'with letters' => 'invalid-cpf',
    'empty' => '',
]);