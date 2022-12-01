<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Configuration;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Enum\SourceType;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DtoRequestParam
{
    public function __construct(
        private readonly SourceType $sourceType = SourceType::JSON,
        private readonly bool $throwException = true
    ) {
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
