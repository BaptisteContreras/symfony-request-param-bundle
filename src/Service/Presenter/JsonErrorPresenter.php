<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use BaptisteContreras\SymfonyRequestParamBundle\Service\Formatter\JsonFormatterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonErrorPresenter implements ErrorPresenterDriverInterface
{
    public function __construct(
        private readonly JsonFormatterInterface $badRequestJsonFormatter,
        private readonly JsonFormatterInterface $technicalErrorJsonFormatter
    ) {
    }

    public function presentBadRequest(RequestDtoException $requestDtoException, Request $request): Response
    {
        return new JsonResponse($this->badRequestJsonFormatter->format(
            [], $requestDtoException, $request, 'Bad request', 400
        ), 400);
    }

    public function presentTechnicalError(RequestDtoException $requestDtoException, Request $request): Response
    {
        return new JsonResponse($this->technicalErrorJsonFormatter->format(
            [], $requestDtoException, $request, 'Technical error', 500
        ), 500);
    }

    public function supports(RequestDtoException $requestDtoException, Request $request): bool
    {
        return true; // TODO for the moment this is the only presenter
    }
}
