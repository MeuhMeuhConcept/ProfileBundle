<?php

namespace MMC\Profile\Component\Provider;

interface UserProfileProviderInterface
{
    /**
     * Get userProfile entity by username and uuid.
     */
    public function findUserProfileByUsernameAndUuid($username, $uuid);
}
