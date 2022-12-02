<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Tool\Builder\Context;

use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Exception\EmptyValidationGroupsException;
use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;

final class DtoProviderContextBuilder
{
    /**
     * @throws EmptyValidationGroupsException
     */
    public static function buildFromAttribute(DtoRequestParam $attribute, string $dtoClass): DtoProviderContext
    {
        return new DtoProviderContext(
            $dtoClass,
            $attribute->getSourceType(),
            $attribute->shouldThrowDeserializationException(),
            $attribute->shouldValidateDto(),
            self::getValidationGroups($attribute),
            $attribute->shouldThrowValidationException()
        );
    }

    /**
     * @return array<string>
     *
     * @throws EmptyValidationGroupsException
     */
    private static function getValidationGroups(DtoRequestParam $dtoRequestParam): array
    {
        $validationGroups = $dtoRequestParam->getValidationGroups();

        if (is_array($validationGroups)) {
            if ($dtoRequestParam->shouldValidateDto() && empty($validationGroups)) {
                throw new EmptyValidationGroupsException();
            }

            return $validationGroups;
        }

        if ($dtoRequestParam->shouldValidateDto() && '' === $validationGroups) {
            throw new EmptyValidationGroupsException();
        }

        return [$validationGroups];
    }
}
