<?php

declare(strict_types=1);

namespace App\Core\Validations\Document;

use App\Core\Validations\Contracts\DocumentValidatorContract;
use App\Core\Validations\Enums\DocumentValidatorEnum;
use http\Exception\RuntimeException;

class DocumentValidator
{
    private DocumentValidatorContract $documentValidator;

    public function __construct(DocumentValidatorEnum $documentType)
    {
        $this->documentValidator = $this->createValidatorInstance($documentType);
    }

    public function validate(string $document): bool
    {
        return $this->documentValidator->isValid($document);
    }

    private function createValidatorInstance(DocumentValidatorEnum $validationType): DocumentValidatorContract
    {
        $validatorClass = $validationType->getValidator();

        $validatorInstance = new $validatorClass();

        if (!$validatorInstance instanceof DocumentValidatorContract) {
            throw new RuntimeException(sprintf(
                'Validator class %s must implement %s',
                $validatorClass,
                DocumentValidatorContract::class,
            ));
        }

        return $validatorInstance;
    }
}
