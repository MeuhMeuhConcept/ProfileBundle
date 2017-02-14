<?php

namespace MMC\Profile\Component\Provider;

class ProfileTypeProvider implements ProfileTypeProviderInterface
{
    public function getTypes()
    {
        return ['TYPE1', 'TYPE2'];
    }
}
