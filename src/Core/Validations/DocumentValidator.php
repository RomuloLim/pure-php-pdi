<?php

declare(strict_types=1);

namespace App\Core\Validations;

use App\Core\Validations\Contracts\DocumentValidatorStrategy;
use App\Core\Validations\Enums\DocumentValidatorEnum;
use RuntimeException;

class DocumentValidator
{
    private DocumentValidatorStrategy $documentValidator;

    public function __construct(DocumentValidatorEnum $documentType)
    {
        $this->documentValidator = $this->createValidatorInstance($documentType);
    }

    public function validate(string $document): bool
    {
        return $this->documentValidator->isValid($document);
    }

    private function createValidatorInstance(DocumentValidatorEnum $validationType): DocumentValidatorStrategy
    {
        $validatorClass = $validationType->getValidator();

        $validatorInstance = new $validatorClass();

        if (!$validatorInstance instanceof DocumentValidatorStrategy) {
            throw new RuntimeException(sprintf(
                'Validator class %s must implement %s',
                $validatorClass,
                DocumentValidatorStrategy::class,
            ));
        }

        return $validatorInstance;
    }
}
