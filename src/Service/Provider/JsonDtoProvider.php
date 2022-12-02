<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Provider;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonDtoProvider implements DtoProviderDriverInterface
{
    public function __construct(private readonly SerializerInterface $serializer, private LoggerInterface $logger)
    {
    }

    public function fromRequest(DtoProviderContext $context, Request $request): mixed
    {
        try {
            return $this->serializer->deserialize($request->getContent(), $context->getDtoClass(), JsonEncoder::FORMAT, []);
        } catch (\Throwable $exception) {
            if ($context->shouldThrowDeserializationException()) {
                throw $exception;
            }

            $this->logger->error(sprintf(
                '[%s] An exception has been thrown and silenced : %s',
                self::class,
                $exception->getMessage()
            ));

            return null;
        }
    }

    public function supports(DtoProviderContext $dtoProviderContext): bool
    {
        return SourceType::JSON === $dtoProviderContext->getSourceType();
    }
}
