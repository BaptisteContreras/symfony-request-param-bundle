<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Provider;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;

/**
 * Just for the autoconfiguration.
 */
interface DtoProviderDriverInterface extends DtoProviderInterface
{
    public function supports(DtoProviderContext $dtoProviderContext): bool;
}
