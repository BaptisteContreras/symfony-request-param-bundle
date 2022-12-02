<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Validator;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface DtoValidatorInterface
{
    public function validateDto(DtoProviderContext $dtoProviderContext, mixed $dto): ConstraintViolationListInterface;
}
