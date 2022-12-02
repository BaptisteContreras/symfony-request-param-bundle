<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationRequestDtoException extends RequestDtoException
{
    public function __construct(
        string $message,
        DtoProviderContext $dtoProviderContext,
        mixed $dto,
        private readonly ConstraintViolationListInterface $constraintViolationList,
    ) {
        parent::__construct($message, 400, $dtoProviderContext, $dto);
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
