<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;

interface UserProfileManipulatorInterface
{
    /**
     * Get a userProfile with giving an user and a profile.
     *
     * @return ProfileInterface
     */
    public function getUserProfile(UserInterface $user, ProfileInterface $profile);

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
     * @return UserProfileInterface
     */
    public function createUserProfile(UserInterface $user, ProfileInterface $profile);

    /**
     * Remove a userProfile.
     *
     * @return UserProfileInterface
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile);

    /**
     * Get owner users for the profile.
     *
     * @return array
     */
    public function getOwners(ProfileInterface $profile);

    /**
     * Change userProfile isOwner attribute to true.
     *
     * @return UserProfileInterface
     */
    public function promoteUserProfile(UserInterface $user, ProfileInterface $profile);

    /**
     * Change userProfile isOwer attribute to false.
     *
     * @return UserProfileInterface
     */
    public function demoteUserProfile(UserInterface $user, ProfileInterface $profile);
}
