<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Provider;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\NoDtoDriverFoundException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Enum\SourceType;
use Symfony\Component\HttpFoundation\Request;

class GenericDtoProvider implements DtoProviderInterface
{
    /**         Properties         **/

    /**         Constructor         **/
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**         Methods         **/
    public function fromRequest(DtoProviderContext $context, Request $request): mixed
    {
        return $this->selectDriver($context->getSourceType())->fromRequest($context, $request);
    }

    private function selectDriver(SourceType $sourceType): DtoProviderDriver
    {
        /** @var DtoProviderDriver $driver */
        foreach ($this->drivers as $driver) {
            if ($driver->supports($sourceType)) {
                return $driver;
            }
        }

        throw new NoDtoDriverFoundException($sourceType);
    }
}
