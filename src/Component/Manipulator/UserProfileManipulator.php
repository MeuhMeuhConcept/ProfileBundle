<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Component\Manipulator\Exception as ManipulatorException;
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
            throw new ManipulatorException\InvalidProfileClassName();
        }

        if (!is_subclass_of($userProfileClassname, UserProfileInterface::class)) {
            throw new ManipulatorException\InvalidUserProfileClassName();
        }

        $this->profileClassname = $profileClassname;
        $this->userProfileClassname = $userProfileClassname;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new ManipulatorException\NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                return $up;
            }
        }

        throw new ManipulatorException\UserProfileNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveProfile(UserInterface $user)
    {
        if ($user->getUserProfiles()->isEmpty()) {
            throw new ManipulatorException\NoUserProfileException();
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($up->getIsActive() == true) {
                return $up->getProfile();
            }
        }

        throw new ManipulatorException\NoActiveProfileException();
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);

        return $up->getIsOwner();
    }

    /**
     * {@inheritdoc}
     */
    public function setActiveProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);

        foreach ($user->getUserProfiles() as $otherUp) {
            if ($up != $otherUp) {
                $otherUp->setIsActive(false);
            }
        }

        $up->setIsActive(true);

        return $up;
    }

    /**
     *{@inheritdoc}
     */
    public function setProfilePriority(UserInterface $user, ProfileInterface $profile, $priority)
    {
        $up = $this->getUserProfile($user, $profile);

        $up->setPriority($priority);

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
            throw new ManipulatorException\ExistingUserProfileException();
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

        return $up;
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
        $up = $this->getUserProfile($user, $profile);

        if ($up->getIsOwner()) {
            throw new ManipulatorException\ExistingOwnerUserProfileException();
        }

        $up->setIsOwner(true);

        return $up;
    }

    /**
     * {@inheritdoc}
     */
    public function demoteUserProfile(UserInterface $user, ProfileInterface $profile)
    {
        $up = $this->getUserProfile($user, $profile);

        if ($this->getOwners($profile) <= 1) {
            throw new ManipulatorException\UnableToDemoteLastOwnerUserProfileException();
        }

        $up->setIsOwner(false);

        return $up;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProfileForUser(UserInterface $user, ProfileInterface $profile)
    {
        if ($this->isOwner($user, $profile)) {
            throw new ManipulatorException\UnableToDeleteOwnerUserProfileException();
        }

        $up = $this->getUserProfile($user, $profile);

        $profile->removeUserProfile($up);
        $user->removeUserProfile($up);

        return $up;
    }
}
