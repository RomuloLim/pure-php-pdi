<?php

declare(strict_types=1);

namespace App\Core\Validations;

use App\Core\Validations\Contracts\DocumentValidatorStrategy;
use App\Core\Validations\Enums\DocumentValidatorEnum;

class DocumentValidator
{
    public function __construct(private DocumentValidatorStrategy $documentValidator){}

    public function validate(string $document): bool
    {
        return $this->documentValidator->isValid($document);
    }

    public static function forType(string $documentType): DocumentValidator
    {
        $validator = DocumentValidatorFactory::create($documentType);

        return new self($validator);
    }
}
