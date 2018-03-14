<?php

namespace SO\ReactUtilsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SOReactUtilsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('so.reactutils.command.yarninstallcommand');
        $definition->replaceArgument(1, $config['yarn_bin_path']);

        $definition = $container->getDefinition('so.reactutils.command.npminstallcommand');
        $definition->replaceArgument(1, $config['npm_bin_path']);

        $definition = $container->getDefinition('so.reactutils.command.npmruncommand');
        $definition->replaceArgument(1, $config['npm_bin_path']);
    }
}