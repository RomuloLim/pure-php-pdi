<?php

declare(strict_types=1);

namespace App\Core\Validations;

use App\Core\Validations\Contracts\DocumentValidatorStrategy;
use App\Core\Validations\Enums\DocumentValidatorEnum;

class DocumentValidator
{
    public function __construct(private readonly DocumentValidatorStrategy $documentValidator){}

    public function validate(string $document): bool
    {
        return $this->documentValidator->isValid($document);
    }

    public static function forType(DocumentValidatorEnum $documentType): DocumentValidator
    {
        $validator = DocumentValidatorFactory::create($documentType->value);

        return new self($validator);
    }
}
