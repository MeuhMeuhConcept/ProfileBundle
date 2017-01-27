<?php

namespace MMC\Profile\Component\Model;

interface ProfileUserAccessorInterface
{
    /**
     * Get userProfiles.
     *
     * @return array
     */
    public function getUserProfiles();

    /**
     * Get owner users.
     *
     * @return array
     */
    public function getOwners();

    /**
     * return true if is owner.
     *
     * @return bool
     */
    public function isOwner(UserProfileAccessorInterface $userProfileAccessor);

    /**
     * Add a userProfile.
     *
     * @return ProfileUserAccessorInterface
     */
    public function addUserProfile(UserProfileInterface $userProfile);
}
