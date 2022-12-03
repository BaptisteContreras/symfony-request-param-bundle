<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Formatter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;

interface JsonFormatterInterface
{
    public function format(array $currentResponse, RequestDtoException $requestDtoException, Request $request, string $defaultMessage, int $httpCode): array;
}
