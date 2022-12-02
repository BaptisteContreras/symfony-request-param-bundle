<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\DependencyInjection;

use BaptisteContreras\SymfonyRequestParamBundle\Service\Presenter\ErrorPresenterDriverInterface;
use BaptisteContreras\SymfonyRequestParamBundle\Service\Provider\DtoProviderDriverInterface;
use BaptisteContreras\SymfonyRequestParamBundle\Service\Validator\DtoValidatorDriverInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class RequestParamExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadServices($container);
        $this->autoConfiguration($container);
    }

    private function loadServices(ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('bundle_services.xml');
    }

    private function autoConfiguration(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(DtoProviderDriverInterface::class)
            ->addTag(Tag::DTO_PROVIDER_DRIVER)
        ;

        $container->registerForAutoconfiguration(DtoValidatorDriverInterface::class)
            ->addTag(Tag::DTO_VALIDATOR_DRIVER)
        ;

        $container->registerForAutoconfiguration(ErrorPresenterDriverInterface::class)
            ->addTag(Tag::ERROR_PRESENTER_DRIVER)
        ;
    }
}
