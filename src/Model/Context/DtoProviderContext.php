<?php


namespace RequestParam\Bundle\Model\Context;


final class DtoProviderContext
{
    public function __construct(private readonly string $dtoClass) {}

    public function getDtoClass(): string
    {
        return $this->dtoClass;
    }

}