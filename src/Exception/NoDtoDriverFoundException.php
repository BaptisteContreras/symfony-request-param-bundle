<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Enum\SourceType;

class NoDtoDriverFoundException extends \Exception
{
    public function __construct(SourceType $sourceType)
    {
        parent::__construct(
            sprintf('No DtoProvider found for source type : %s', $sourceType->value)
        );
    }
}
