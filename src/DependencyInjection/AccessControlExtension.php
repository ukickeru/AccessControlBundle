<?php

namespace ukickeru\AccessControlBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ukickeru\AccessControlBundle\Infrastructure\Controller\Http\AuthenticationController;
use ukickeru\AccessControlBundle\Application\Security\Authentication\AppAuthenticator;

class AccessControlExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config/packages')
        );
        $loader->load('doctrine.yaml');
        $loader->load('security.yaml');
        $loader->load('twig.yaml');
    }

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(AppAuthenticator::class);
        $definition->setArguments([
            '$indexPath' => $config['paths']['index_path'],
        ]);

        $definition = $container->getDefinition(AuthenticationController::class);
        $definition->setArguments([
            '$pathToRedirectAfterLogin' => $config['paths']['redirect_after_login_path'],
            '$pathToRedirectAfterLogout' => $config['paths']['redirect_after_logout_path'],
        ]);
    }
}
