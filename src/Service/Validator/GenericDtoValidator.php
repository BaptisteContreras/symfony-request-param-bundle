<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Validator;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\NoDtoValidatorDriverFoundException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class GenericDtoValidator implements DtoValidatorInterface
{
    /**
     * @param iterable<DtoValidatorDriverInterface> $drivers
     */
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**
     * @throws NoDtoValidatorDriverFoundException
     */
    public function validateDto(DtoProviderContext $dtoProviderContext, mixed $dto): ConstraintViolationListInterface
    {
        return $this->selectDriver($dtoProviderContext)->validateDto($dtoProviderContext, $dto);
    }

    /**
     * @throws NoDtoValidatorDriverFoundException
     */
    private function selectDriver(DtoProviderContext $dtoProviderContext): DtoValidatorDriverInterface
    {
        /** @var DtoValidatorDriverInterface $driver */
        foreach ($this->drivers as $driver) {
            if ($driver->supports($dtoProviderContext)) {
                return $driver;
            }
        }

        throw new NoDtoValidatorDriverFoundException();
    }
}
