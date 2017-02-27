<?php

namespace MMC\Profile\Bundle\ProfileBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MMCProfileExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        $container->setParameter('mmc_profile.profile_class', $config['profile_class']);
        $container->setParameter('mmc_profile.userProfile_class', $config['userProfile_class']);
        $container->setParameter('mmc_profile.user_class', $config['user_class']);
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrine = [
            'orm' => [
                'entity_managers' => [
                    'default' => [
                        'filters' => [
                            'softdeleteable' => [
                                'class' => 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter',
                                'enabled' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $container->prependExtensionConfig('doctrine', $doctrine);

        $stof_doctrine_extensions = [
            'default_locale' => '%locale%',
            'orm' => [
                'default' => [
                    'softdeleteable' => true,
                ],
            ],
        ];

        $container->prependExtensionConfig('stof_doctrine_extensions', $stof_doctrine_extensions);
    }
}
