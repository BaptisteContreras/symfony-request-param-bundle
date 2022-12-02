<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ErrorPresenterDriverInterface
{
    public function presentBadRequest(RequestDtoException $requestDtoException, Request $request): Response;

    public function presentTechnicalError(RequestDtoException $requestDtoException, Request $request): Response;

    public function supports(RequestDtoException $requestDtoException, Request $request): bool;
}
