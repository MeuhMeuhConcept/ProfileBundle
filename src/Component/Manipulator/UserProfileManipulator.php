<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Manipulator\Exception\ExistingUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\InvalidProfileClassName;
use MMC\Profile\Component\Manipulator\Exception\InvalidUserProfileClassName;
use MMC\Profile\Component\Manipulator\Exception\NoUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UnableToDeleteOwnerUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UnableToDemoteLastOwnerUserProfileException;
use MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileInterface;

class UserProfileManipulator implements UserProfileManipulatorInterface
{
    private $profileClassname;
    private $userProfileClassname;

    public function __construct(
        $profileClassname,
        $userProfileClassname
    ) {
        if (!is_subclass_of($profileClassname, ProfileInterface::class)) {
            throw new InvalidProfileClassName();
        }

        if (!is_subclass_of($userProfileClassname, UserProfileInterface::class)) {
            throw new InvalidUserProfileClassName();
        }

        $this->profileClassname = $profileClassname;
        $this->userProfileClassname = $userProfileClassname;
    }

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

        return $up;
    }

    /**
     * {@inheritdoc}
     */
    public function createUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $existingUP = false;

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $existingUP = true;
            }
        }

        if ($existingUP) {
            throw new ExistingUserProfileException();
        }

        $class = $this->userProfileClassname;
        $up = new $class();

        if (count($profile->getUserProfiles()) == 0) {
            $up->setIsOwner(true);
        } else {
            $up->setIsOwner(false);
        }
        $up->setPriority(0);
        $up->setUser($user);
        $up->setProfile($profile);
        $this->setActiveProfile($user, $profile);

        return $up;
    }

    /**
     * {@inheritdoc}
     */
    public function createProfileForUser(UserInterface $user)
    {
        $class = $this->profileClassname;
        $profile = new $class();
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
    public function promoteUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $profileMatches = false;

        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $selectedUserProfile = $up;
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new UserProfileNotFoundException();
        }

        $selectedUserProfile->setIsOwner(true);
    }

    /**
     * {@inheritdoc}
     */
    public function demoteUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $profileMatches = false;

        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $selectedUserProfile = $up;
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new UserProfileNotFoundException();
        }

        if ($this->getOwners($profile) <= 1) {
            throw new UnableToDemoteLastOwnerUserProfileException();
        }

        $selectedUserProfile->setIsOwner(false);
    }

    /**
     * {@inheritdoc}
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile)
    {
        $profileMatches = false;
        $selectedUserProfile;

        if ($user->getUserProfiles()->isEmpty()) {
            throw new NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $selectedUserProfile = $up;
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new UserProfileNotFoundException();
        }

        if ($this->isOwner($user, $profile)) {
            throw new UnableToDeleteOwnerUserProfileException();
        }

        $profile->removeUserProfile($selectedUserProfile);
        $user->removeUserProfile($selectedUserProfile);

        return $selectedUserProfile;
    }
}
