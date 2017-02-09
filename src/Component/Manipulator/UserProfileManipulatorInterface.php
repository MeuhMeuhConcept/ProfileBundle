<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;

interface UserProfileManipulatorInterface
{
    /**
     * Get the active profile of user.
     *
     * @return ProfileInterface
     */
    public function getActiveProfile(UserInterface $user);

    /**
     * Get true if user is profile's owner.
     *
     * @return bool
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile);

    /**
     * Set profile to active for the user.
     *
     * @return UserInterface
     */
    public function setActiveProfile(UserInterface $user, ProfileInterface $profile);

    /**
     * Create a userProfile.
     *
     * @return ProfileInterface
     */
    public function createUserProfile(UserInterface $user, ProfileInterface $profile);

    /**
     * Create profile and userProfile for user.
     *
     * @return ProfileInterface
     */
    public function createProfileForUser(UserInterface $user);

    /**
     * Remove a userProfile.
     *
     * @return UserInterface
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile);

    /**
     * Get owner users for the profile.
     *
     * @return array
     */
    public function getOwners(ProfileInterface $profile);
}
