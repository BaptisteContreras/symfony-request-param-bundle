<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Handler\Error;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use BaptisteContreras\SymfonyRequestParamBundle\Exception\ValidationRequestDtoException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    public function handleValidationError(DtoProviderContext $dtoProviderContext, ConstraintViolationListInterface $constraintViolationList, mixed $dto): RequestDtoException
    {
        return new ValidationRequestDtoException('DTO validation error', $dtoProviderContext, $dto, $constraintViolationList);
    }
}
