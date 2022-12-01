<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Request\ParamConverter;

use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Exception\MissingRequestControllerAttributeException;
use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestParameterMustBeObjectException;
use BaptisteContreras\SymfonyRequestParamBundle\Service\Provider\DtoProviderInterface;
use BaptisteContreras\SymfonyRequestParamBundle\Tool\Builder\Context\DtoProviderContextBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DtoParamConverter implements ParamConverterInterface
{
    private const CONTROLLER_ATTRIBUTE = '_controller';

    public function __construct(private readonly DtoProviderInterface $dtoProvider)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws MissingRequestControllerAttributeException
     * @throws RequestParameterMustBeObjectException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if (!$controllerAttribute = $request->attributes->get(self::CONTROLLER_ATTRIBUTE)) {
            throw new MissingRequestControllerAttributeException(self::CONTROLLER_ATTRIBUTE);
        }

        $taggedParameters = $this->getTaggedParameters($controllerAttribute);

        /** @var \ReflectionParameter $taggedDtoParameter */
        foreach ($taggedParameters as $taggedDtoParameter) {
            $dtoRequestParamAttributes = $taggedDtoParameter->getAttributes(DtoRequestParam::class);
            $paramAttribute = array_shift($dtoRequestParamAttributes);

            $dto = $this->dtoProvider->fromRequest(
                DtoProviderContextBuilder::buildFromAttribute($paramAttribute->newInstance(), $taggedDtoParameter->getType()->getName()),
                $request
            );

            $request->attributes->set($taggedDtoParameter->getName(), $dto);
        }

        return !empty($dtoParameters);
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration instanceof AutoProvideRequestDto;
    }

    /**
     * @return array<\ReflectionParameter>
     *
     * @throws \ReflectionException
     * @throws RequestParameterMustBeObjectException
     */
    private function getTaggedParameters(string $controllerAttribute): array
    {
        $controllerAttributeExploded = explode('::', $controllerAttribute);

        $controllerClass = $controllerAttributeExploded[0];
        $controllerMethod = $controllerAttributeExploded[1];

        $controllerReflection = new \ReflectionClass($controllerClass);

        return array_filter($controllerReflection->getMethod($controllerMethod)->getParameters(), function (\ReflectionParameter $reflectionParameter) {
            $canAutoProvide = 1 === count($reflectionParameter->getAttributes(DtoRequestParam::class));

            if ($canAutoProvide && $reflectionParameter->getType()->isBuiltin()) {
                throw new RequestParameterMustBeObjectException($reflectionParameter->getName());
            }

            return $canAutoProvide;
        });
    }
}
