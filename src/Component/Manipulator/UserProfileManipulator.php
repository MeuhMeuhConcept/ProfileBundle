<?php

namespace MMC\Profile\Component\Manipulator;

use MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile;
use MMC\Profile\Component\Manipulator\Exception\ProfileNotFoundException;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserProfileManipulator implements UserProfileManipulatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getActiveProfile(UserInterface $user)
    {
        $userProfiles = $user->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getIsActive() == 'true') {
                return $up->getProfile();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserInterface $user, ProfileInterface $profile)
    {
        $userProfiles = $user->getUserProfiles();
        foreach ($userProfiles as $up) {
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
        $isSet = false;
        $userProfiles = $user->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getProfile() == $profile) {
                $up->setIsActive(true);
                $isSet = true;
            } else {
                $up->setIsActive(false);
            }
        }

        if ($isSet == false) {
            throw new ProfileNotFoundException();
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
        if ($user->getUserProfiles() == null) {
            throw new NotFoundHttpException('La liaison n\'existe pas');
        }

        $profileMatches = false;

        foreach ($user->getUserProfiles() as $up) {
            if ($profile == $up->getProfile()) {
                $profileMatches = true;
            }
        }

        if ($profileMatches == false) {
            throw new NotFoundHttpException('La liaison n\'existe pas');
        }

        if ($this->getActiveProfile($user) == $profile) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas supprimer le profil actif');
        }

        if (count($this->getOwners($profile)) <= 1) {
            throw new AccessDeniedHttpException('Impossible de supprimer le dernier propriétaire');
        }

        foreach ($user->getUserProfiles() as $up) {
            if ($up->getProfile() == $profile) {
                $user->removeUserProfile($up);
            }
        }

        return $this;
    }
}
