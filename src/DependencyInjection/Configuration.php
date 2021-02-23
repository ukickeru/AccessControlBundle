<?php
namespace ukickeru\AccessControlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('access_control_bundle');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('index_path')
                    ->isRequired()
                    ->info('Путь к индексной странице приложения')
                    ->validate()
                        ->ifTrue(function ($indexPath) {
                            return (is_string($indexPath) === false);
                        })
                        ->thenInvalid('Имя пути должно быть строкой!')
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('redirect_after_login_path')
                    ->isRequired()
                    ->info('Путь, по которому перенаправляется пользователь после авторизации')
                    ->validate()
                        ->ifTrue(function ($loginPath) {
                            return (is_string($loginPath) === false);
                        })
                        ->thenInvalid('Имя пути должно быть строкой!')
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('redirect_after_logout_path')
                    ->isRequired()
                    ->info('Путь, по которому перенаправляется пользователь после выхода из системы')
                    ->validate()
                        ->ifTrue(function ($logoutPath) {
                            return (is_string($logoutPath) === false);
                        })
                        ->thenInvalid('Имя пути должно быть строкой!')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
