<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Provider;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\NoDtoProviderDriverFoundException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use Symfony\Component\HttpFoundation\Request;

class GenericDtoProvider implements DtoProviderInterface
{
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**
     * @throws NoDtoProviderDriverFoundException
     */
    public function fromRequest(DtoProviderContext $context, Request $request): mixed
    {
        return $this->selectDriver($context)->fromRequest($context, $request);
    }

    /**
     * @throws NoDtoProviderDriverFoundException
     */
    private function selectDriver(DtoProviderContext $context): DtoProviderDriverInterface
    {
        /** @var DtoProviderDriverInterface $driver */
        foreach ($this->drivers as $driver) {
            if ($driver->supports($context)) {
                return $driver;
            }
        }

        throw new NoDtoProviderDriverFoundException($context->getSourceType());
    }
}
