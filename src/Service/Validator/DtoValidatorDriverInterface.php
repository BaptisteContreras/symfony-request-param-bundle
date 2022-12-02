<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Validator;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;

interface DtoValidatorDriverInterface extends DtoValidatorInterface
{
    /**
     * @return array<string>
     */
    public function selectValidationGroup(DtoProviderContext $dtoProviderContext, mixed $dto): array;

    public function supports(DtoProviderContext $dtoProviderContext): bool;
}
