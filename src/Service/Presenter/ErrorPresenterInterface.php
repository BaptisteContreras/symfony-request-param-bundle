<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ErrorPresenterInterface
{
    public function presentResponse(RequestDtoException $requestDtoException, Request $request): Response;
}
