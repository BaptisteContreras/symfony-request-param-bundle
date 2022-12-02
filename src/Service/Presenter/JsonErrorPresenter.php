<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonErrorPresenter implements ErrorPresenterDriverInterface
{
    public function presentBadRequest(RequestDtoException $requestDtoException, Request $request): Response
    {
        return new JsonResponse([
            'error' => true,
            'success' => false,
            'message' => $requestDtoException->getMessage() ?: 'Bad request',
            'status' => 400,
        ], 400);
    }

    public function presentTechnicalError(RequestDtoException $requestDtoException, Request $request): Response
    {
        return new JsonResponse([
            'error' => true,
            'success' => false,
            'message' => $requestDtoException->getMessage() ?: 'Technical error',
            'status' => 500,
            ], 500);
    }

    public function supports(RequestDtoException $requestDtoException, Request $request): bool
    {
        return true; // TODO for the moment this is the only presenter
    }
}
