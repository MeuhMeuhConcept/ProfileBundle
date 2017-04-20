<?php

namespace MMC\Profile\Component\Model;

interface UserProfileAccessorInterface
{
    /**
     * Get userProfiles.
     *
     * @return ArrayCollection
     */
    public function getUserProfiles();

    /**
     * Add an userProfile.
     *
     * @return UserProfileAccessorInterface
     */
    public function addUserProfile(UserProfileInterface $up);

    /**
     * Remove an userProfile.
     *
     * @return UserProfileAccessorInterface
     */
    public function removeUserProfile(UserProfileInterface $up);
}
