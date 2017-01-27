<?php

namespace MMC\Profile\Component\Model;

interface UserProfileAccessorInterface
{
    /**
     * Get userProfiles.
     *
     * @return array
     */
    public function getUserProfiles();

    /**
     * Get the active profile.
     *
     * @return Profile
     */
    public function getActiveProfile();

    /**
     * Get true if is profile's owner.
     *
     * @return bool
     */
    public function isOwner(Profile $profile);

    /**
     * Set profile to active.
     *
     * @return UserProfileAccessorInterface
     */
    public function setActiveProfile(Profile $profile);

    /**
     * Add a userProfile.
     *
     * @return UserProfileAccessorInterface
     */
    public function addUserProfile(UserProfileInterface $userProfile);
}
