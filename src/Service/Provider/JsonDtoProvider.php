<?php

namespace RequestParam\Bundle\Service\Provider;

use RequestParam\Bundle\Model\Context\DtoProviderContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class JsonDtoProvider implements DtoProviderInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function fromRequest(DtoProviderContext $context, Request $request): mixed
    {
    }
}
