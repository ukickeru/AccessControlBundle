<?php

namespace ukickeru\AccessControlBundle\DependencyInjection\Compiler;

use ukickeru\AccessControlBundle\Model\ApplicationRoutesContainer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ApplicationRoutesContainerRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ApplicationRoutesContainer::class)) {
            return;
        }

        $applicationRoutesContainerDefinition = $container->findDefinition(ApplicationRoutesContainer::class);

        $taggedServices = $container->findTaggedServiceIds('ukickeru.access-control.routes-container');

        $routesContainerReferences = [];
        foreach ($taggedServices as $id => $tags) {
            $routesContainerReferences[] = new Reference($id);
        }

        $applicationRoutesContainerDefinition->setArguments(['$routesContainers' => $routesContainerReferences]);
    }
}
