<?php

namespace MMC\Profile\Component\Provider;

class ProfileTypeProvider implements ProfileTypeProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTypes()
    {
        return [
            'TYPE1' => 'TYPE1',
            'TYPE2' => 'TYPE2',
        ];
    }
}
