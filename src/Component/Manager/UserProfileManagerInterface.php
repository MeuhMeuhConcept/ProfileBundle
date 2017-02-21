<?php

namespace MMC\Profile\Component\Manager;

use MMC\Profile\Component\Model\UserProfileInterface;

interface UserProfileManagerInterface
{
    /**
     * Persist a user profile.
     */
    public function saveUserProfile(UserProfileInterface $userProfile);

    /**
     * remove a userProfile.
     */
    public function removeUserProfile(UserProfileInterface $userProfile);

    /**
     * Make a flush.
     */
    public function flush();
}
