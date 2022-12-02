<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Model\Context;

final class DtoProviderContext
{
    public function __construct(
        private readonly string $dtoClass,
        private readonly string $sourceType,
        private readonly bool $throwDeserializationException,
        private readonly bool $validateDto,
        private readonly array $validationGroups,
        private readonly bool $throwValidationException
    ) {
    }

    public function getDtoClass(): string
    {
        return $this->dtoClass;
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

    public function getValidationGroups(): array
    {
        return $this->validationGroups;
    }

    public function shouldThrowValidationException(): bool
    {
        return $this->throwValidationException;
    }
}
