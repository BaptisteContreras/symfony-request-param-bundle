<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

use BaptisteContreras\SymfonyRequestParamBundle\Model\Context\DtoProviderContext;

abstract class RequestDtoException extends \Exception
{
    public function __construct(
        string $message,
        protected readonly int $httpCode,
        protected readonly DtoProviderContext $dtoProviderContext,
        protected readonly mixed $dto
    ) {
        parent::__construct($message);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getDtoProviderContext(): DtoProviderContext
    {
        return $this->dtoProviderContext;
    }

    public function getDto(): mixed
    {
        return $this->dto;
    }
}
