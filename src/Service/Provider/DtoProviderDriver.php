<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Provider;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Enum\SourceType;

/**
 * Just for the autoconfiguration.
 */
interface DtoProviderDriver extends DtoProviderInterface
{
    public function supports(SourceType $sourceType): bool;
}
