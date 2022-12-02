<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

class NoDtoProviderDriverFoundException extends \Exception
{
    public function __construct(string $sourceType)
    {
        parent::__construct(
            sprintf('No DtoProvider found for source type : %s', $sourceType)
        );
    }
}
