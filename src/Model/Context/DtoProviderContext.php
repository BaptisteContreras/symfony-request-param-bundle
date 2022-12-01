<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Model\Context;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Enum\SourceType;

final class DtoProviderContext
{
    public function __construct(
        private readonly string $dtoClass,
        private readonly SourceType $sourceType,
        private readonly bool $throwException
    ) {
    }

    public function getDtoClass(): string
    {
        return $this->dtoClass;
    }

    public function getSourceType(): SourceType
    {
        return $this->sourceType;
    }

    public function shouldThrowException(): bool
    {
        return $this->throwException;
    }
}
