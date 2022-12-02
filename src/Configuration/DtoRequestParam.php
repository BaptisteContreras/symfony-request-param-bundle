<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Configuration;

use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DtoRequestParam
{
    public function __construct(
        private readonly string $sourceType = SourceType::JSON,
        private readonly bool $throwDeserializationException = true,
        private readonly bool $validateDto = true,
        private readonly array|string $validationGroups = ['Default'],
        private readonly bool $throwValidationException = true
    ) {
    }

    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    public function shouldThrowDeserializationException(): bool
    {
        return $this->throwDeserializationException;
    }

    public function shouldValidateDto(): bool
    {
        return $this->validateDto;
    }

    public function getValidationGroups(): array|string
    {
        return $this->validationGroups;
    }

    public function shouldThrowValidationException(): bool
    {
        return $this->throwValidationException;
    }
}
