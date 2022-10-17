<?php


namespace RequestParam\Bundle\Configuration;


use RequestParam\Bundle\Model\Enum\SourceType;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DtoRequestParam
{
    public function __construct(private readonly SourceType $sourceType) {}

    public function getSourceType(): SourceType
    {
        return $this->sourceType;
    }
}