<?php

namespace Book\RequestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BookRequestExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        if (isset($config['enabled']) && $config['enabled']) {
            $loader->load('test.xml');
            if (!isset($config['my_type'])) {
                throw new \InvalidArgumentException(
                    'The "my_type" option must be set'
                ); }
            $container->setParameter('book_request.my_service_type', $config['my_type']);
            $container->setParameter('book_request.my_type',         $config['my_type']);
        }



//        file_put_contents('/Applications/MAMP/htdocs/symfony2/_a.txt', var_export($container->getParameterBag()->all(), true).PHP_EOL, FILE_APPEND);
    }

//
//    public function getXsdValidationBasePath()
//    {
//        return __DIR__.'/../Resources/config/';
//    }
//
//    public function getNamespace()
//    {
//        return 'http://www.example.com/symfony/schema/';
//    }

}
