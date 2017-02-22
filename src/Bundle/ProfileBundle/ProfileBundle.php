<?php

namespace MMC\Profile\Bundle\ProfileBundle;

use MMC\Profile\Bundle\ProfileBundle\DependencyInjection\MMCProfileExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ProfileBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function getContainerExtension()
    {
        return new MMCProfileExtension();
    }
}
