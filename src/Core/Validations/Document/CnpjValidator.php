<?php

declare(strict_types=1);

namespace App\Core\Validations\Document;

use App\Core\Validations\Contracts\DocumentValidatorContract;

class CnpjValidator implements DocumentValidatorContract
{
    public function isValid(string $document): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $document);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cnpj[12] != $digit1) {
            return false;
        }

        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return ($cnpj[13] == $digit2);
    }
}
