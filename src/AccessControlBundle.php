<?php

namespace ukickeru\AccessControlBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AccessControlBundle extends Bundle
{
    /**
     * Add custom compiler pass to DI compilation process
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    /**
     * This is needed to point AccessControlBundle resource alias to root bundle dir, instead of ./src
     * Because before Symfony 5 by convention for bundle directory structure,
     * resources used to be located in ./src/Resources folder under ./src. But now not
     * @see https://github.com/symfony/symfony/blob/master/UPGRADE-5.0.md#httpkernel
     *
     * @return string
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
