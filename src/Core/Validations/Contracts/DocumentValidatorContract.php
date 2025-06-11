<?php

declare(strict_types=1);

namespace App\Core\Validations\Contracts;

interface DocumentValidatorContract
{
    public function isValid(string $document): bool;
}
