<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Formatter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;

class ValidationErrorJsonFormatter implements JsonFormatterInterface
{
    public function __construct(private readonly ?JsonFormatterInterface $decorated = null)
    {
    }

    public function format(array $currentResponse, RequestDtoException $requestDtoException, Request $request, string $defaultMessage, int $httpCode): array
    {
        $errors = [];
        $currentResponse['errors'] = $errors;

        return $this->decorated ? $this->decorated->format(
            $currentResponse, $requestDtoException, $request, $defaultMessage, $httpCode
        ) : $currentResponse;
    }
}
