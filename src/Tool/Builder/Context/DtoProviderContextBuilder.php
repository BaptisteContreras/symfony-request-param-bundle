<?php

namespace RequestParam\Bundle\Tool\Builder\Context;

use RequestParam\Bundle\Configuration\DtoRequestParam;
use RequestParam\Bundle\Model\Context\DtoProviderContext;

final class DtoProviderContextBuilder
{
    public static function buildFromAttribute(DtoRequestParam $attribute, string $dtoClass): DtoProviderContext
    {
        return new DtoProviderContext($dtoClass);
    }
}
