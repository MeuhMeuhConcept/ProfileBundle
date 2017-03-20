<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const CAN_GET_ACTIVE_PROFILE = 'CAN_GET_ACTIVE_PROFILE';
    const CAN_BROWSE_USER_PROFILES_BY_USER = 'CAN_BROWSE_USER_PROFILES_BY_USER';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CAN_GET_ACTIVE_PROFILE, self::CAN_BROWSE_USER_PROFILES_BY_USER])) {
            return false;
        }

        if (!$subject instanceof UserInterface) {
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
            case self::CAN_GET_ACTIVE_PROFILE:
                return $this->canGetActive();
            case self::CAN_BROWSE_USER_PROFILES_BY_USER:
                return $this->canBrowseUserProfilesByUser($subject, $user);
        }
    }

    private function canGetActive()
    {
        return true;
    }

    private function canBrowseUserProfilesByUser(UserInterface $user, UserInterface $currentUser)
    {
        if ($currentUser != $user) {
            return false;
        }

        return true;
    }
}
