<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Tool\Builder\Context;

use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;

final class DtoProviderContextBuilder
{
    public static function buildFromAttribute(DtoRequestParam $attribute, string $dtoClass): DtoProviderContext
    {
        return new DtoProviderContext($dtoClass, $attribute->getSourceType(), $attribute->shouldThrowException());
    }
}
