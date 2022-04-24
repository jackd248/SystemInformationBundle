<?php

namespace Kmi\SystemInformationBundle\DependencyInjection;

use Kmi\SystemInformationBundle\SystemInformationBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SystemInformationBundleExtension extends Extension
{

    /**
     * Load the configuration and inject its values
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $container->setParameter('kmi_system_information_bundle', $config);
    }

    /**
     * https://stackoverflow.com/a/64614381
     */
    public function prepend(ContainerBuilder $container)
    {
        $thirdPartyBundlesViewFileLocator = (new FileLocator(__DIR__ . '/../Resources/views/bundles'));

        $container->loadFromExtension('twig', [
            'paths' => [
                $thirdPartyBundlesViewFileLocator->locate('SonataAdminBundle') => 'SonataAdmin',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return SystemInformationBundle::BUNDLE_CONFIG_NAME;
    }
}