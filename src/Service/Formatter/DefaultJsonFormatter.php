<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Formatter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;

class DefaultJsonFormatter implements JsonFormatterInterface
{
    public function __construct(private readonly ?JsonFormatterInterface $decorated = null)
    {
    }

    public function format(array $currentResponse, RequestDtoException $requestDtoException, Request $request, string $defaultMessage, int $httpCode): array
    {
        $currentResponse = array_merge($currentResponse, [
            'error' => true,
            'success' => false,
            'message' => $requestDtoException->getMessage() ?: $defaultMessage,
            'status' => $httpCode,
        ]);

        return $this->decorated ? $this->decorated->format(
            $currentResponse, $requestDtoException, $request, $defaultMessage, $httpCode
        ) : $currentResponse;
    }
}
