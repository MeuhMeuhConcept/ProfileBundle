<?php

namespace MMC\Profile\Component\Provider;

interface ProfileProviderInterface
{
    /**
     * Get profile entity by uuid.
     */
    public function findProfileByUuid($uuid);
}
