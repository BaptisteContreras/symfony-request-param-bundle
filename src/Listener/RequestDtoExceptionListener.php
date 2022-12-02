<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Listener;

use BaptisteContreras\SymfonyRequestParamBundle\Exception\RequestDtoException;
use BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter\ErrorPresenterInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class RequestDtoExceptionListener
{
    public function __construct(private readonly ErrorPresenterInterface $errorPresenter)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($this->shouldHandleException($event)) {
            $event->stopPropagation();
            $event->allowCustomResponseCode();

            $event->setResponse($this->errorPresenter->presentResponse(
                $event->getThrowable(),
                $event->getRequest()
            ));
        }
    }

    private function shouldHandleException(ExceptionEvent $event): bool
    {
        return $event->getThrowable() instanceof RequestDtoException;
    }
}
