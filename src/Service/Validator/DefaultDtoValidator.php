<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Validator;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DefaultDtoValidator implements DtoValidatorDriverInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validateDto(DtoProviderContext $dtoProviderContext, mixed $dto): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto, null, $this->selectValidationGroup($dtoProviderContext, $dto));
    }

    public function selectValidationGroup(DtoProviderContext $dtoProviderContext, mixed $dto): array
    {
        return $dtoProviderContext->getValidationGroups();
    }

    public function supports(DtoProviderContext $dtoProviderContext): bool
    {
        return true;
    }
}
