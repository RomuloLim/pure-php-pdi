<?php

declare(strict_types=1);

namespace App\Core\Validations\Document;

use App\Core\Validations\Contracts\DocumentValidatorContract;
use App\Core\Validations\Enums\DocumentValidatorEnum;

class DocumentValidator
{
    private DocumentValidatorContract $documentValidator;
    public function __construct(DocumentValidatorEnum $documentType)
    {
        $validatorClass = $documentType->getValidator();
        $this->documentValidator = new $validatorClass();
    }

    public function validate(string $document): bool
    {
        return $this->documentValidator->isValid($document);
    }
}
