<?php

namespace Applisun\AireJeuxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Applisun\AireJeuxBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApplisunAireJeuxBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
}
