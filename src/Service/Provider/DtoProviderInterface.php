<?php

namespace RequestParam\Bundle\Service\Provider;

use RequestParam\Bundle\Model\Context\DtoProviderContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Build a DTO given a source and a context.
 */
interface DtoProviderInterface
{
    /**
     * Returns a DTO from request using the given $context.
     */
    public function fromRequest(DtoProviderContext $context, Request $request): mixed;
}
