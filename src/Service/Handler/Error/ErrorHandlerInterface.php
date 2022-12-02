<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Handler\Error;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ErrorHandlerInterface
{
    public function handleValidationError(DtoProviderContext $dtoProviderContext, ConstraintViolationListInterface $constraintViolationList, mixed $dto): RequestDtoException;
}
