<?php

declare(strict_types=1);

namespace App\Core\Validations\Document;

use App\Core\Validations\Contracts\DocumentValidatorStrategy;

class CpfValidator implements DocumentValidatorStrategy
{
    public function isValid(string $document): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $document);

        if (!$cpf) {
            return false;
        }

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
