<?php

namespace Smart\AuthenticationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class SmartAuthenticationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array<array> $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('admin_extension.xml');
        $loader->load('security.xml');
        $loader->load('fixtures.xml');
    }

    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     * @return void
     */
    public function prepend(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('config.yml');
    }
}
