<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\NoErrorPresenterFoundException;
use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenericErrorPresenter implements ErrorPresenterInterface
{
    /**
     * @param iterable<ErrorPresenterDriverInterface> $drivers
     */
    public function __construct(private readonly iterable $drivers)
    {
    }

    /**
     * @throws NoErrorPresenterFoundException
     */
    public function presentResponse(RequestDtoException $requestDtoException, Request $request): Response
    {
        $driver = $this->selectDriver($requestDtoException, $request);

        return match ($requestDtoException->getHttpCode()) {
            400 => $driver->presentBadRequest($requestDtoException, $request),
            default => $driver->presentTechnicalError($requestDtoException, $request),
        };
    }

    /**
     * @throws NoErrorPresenterFoundException
     */
    private function selectDriver(RequestDtoException $requestDtoException, Request $request): ErrorPresenterDriverInterface
    {
        /** @var ErrorPresenterDriverInterface $driver */
        foreach ($this->drivers as $driver) {
            if ($driver->supports($requestDtoException, $request)) {
                return $driver;
            }
        }

        throw new NoErrorPresenterFoundException();
    }
}
