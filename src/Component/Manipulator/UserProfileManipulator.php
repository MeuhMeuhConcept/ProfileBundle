<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile;
use MMC\Profile\Component\Manipulator\Exception\NoUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UnableToDeleteActiveUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UnableToDeleteLastOwnerUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;

class UserProfileManipulator implements UserProfileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getActiveProfile(UserInterface $user)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($up->getIsActive() == true) {
                return $up->getProfile();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        $profileMatches = false;

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new UserProfileNotFoundException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($up->getProfile() == $profile && $up->getIsOwner() == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setActiveProfile(UserInterface $user, ProfileInterface $profile)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        $isSet = false;
        foreach ($user->getUserProfiles() as $up) {
            if ($up->getProfile() == $profile) {
                $up->setIsActive(true);
                $isSet = true;
            } else {
                $up->setIsActive(false);
            }
        }

        if ($isSet == false) {
            throw new UserProfileNotFoundException();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = new UserProfile();
        $up->setUser($user);
        $up->setProfile($profile);

        if (count($profile->getUserProfiles()) == 0) {
            $up->setIsOwner(true);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function createProfileForUser(UserInterface $user)
    {
        $profile = new Profile();
        $this->createUserProfile($user, $profile);

        return $profile;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwners(ProfileInterface $profile)
    {
        $owners = [];
        $userProfiles = $profile->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getIsOwner()) {
                $owners[] = $up->getUser();
            }
        }

        return $owners;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        $profileMatches = false;

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new UserProfileNotFoundException();
        }

        if ($this->getActiveProfile($user) == $profile) {
            throw new UnableToDeleteActiveUserProfileException();
        }

        foreach ($this->getOwners($profile) as $owner) {
        }

        if (count($this->getOwners($profile)) <= 1) {
            throw new UnableToDeleteLastOwnerUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($up->getProfile() == $profile) {
                $user->removeUserProfile($up);
                $profile->removeUserProfile($up);
            }
        }

        return $this;
    }
}
