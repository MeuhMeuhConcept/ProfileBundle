<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{
    const CAN_ASSOCIATE_PROFILE = 'CAN_ASSOCIATE_PROFILE';
    const CAN_GET_OWNERS = 'CAN_GET_OWNERS';
    const CAN_BROWSE_USERS = 'CAN_BROWSE_USERS';
    const CAN_BROWSE_USER_PROFILES_BY_PROFILE = 'CAN_BROWSE_USER_PROFILES_BY_PROFILE';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CAN_ASSOCIATE_PROFILE, self::CAN_GET_OWNERS, self::CAN_BROWSE_USERS, self::CAN_BROWSE_USER_PROFILES_BY_PROFILE])) {
            return false;
        }
        if (!$subject instanceof ProfileInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::CAN_ASSOCIATE_PROFILE:
                return $this->canAssociate($subject, $user);
            case self::CAN_GET_OWNERS:
                return $this->canGetOwners();
            case self::CAN_BROWSE_USERS:
                return $this->canBrowseUsers($subject, $user);
            case self::CAN_BROWSE_USER_PROFILES_BY_PROFILE:
                return $this->canBrowseUserProfilesByProfile($subject, $user);
        }
    }

    private function canAssociate(ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $up) {
            if ($up->getUser() == $user && $up->getIsOwner()) {
                return true;
            }
        }

        return false;
    }

    private function canGetOwners()
    {
        return true;
    }

    private function canBrowseUsers(ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $up) {
            if ($up->getUser() == $user && $up->getIsOwner()) {
                return true;
            }
        }

        return false;
    }

    private function canBrowseUserProfilesByProfile(ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $up) {
            if ($up->getUser() == $user && $up->getIsOwner()) {
                return true;
            }
        }

        return false;
    }
}
