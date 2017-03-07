<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditUserProfileVoter extends Voter
{
    const ACTIVATE = 'CAN_ACTIVATE_USERPROFILE';
    const PRIORITY = 'CAN_SET_PRIORITY_USERPROFILE';
    const DELETE = 'CAN_DELETE_USERPROFILE';
    const PROMOTE = 'CAN_PROMOTE_USERPROFILE';
    const DEMOTE = 'CAN_DEMOTE_USERPROFILE';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::ACTIVATE, self::PRIORITY, self::DELETE, self::PROMOTE, self::DEMOTE])) {
            return false;
        }
        if (!$subject instanceof UserProfileInterface) {
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
            case self::PRIORITY:
                return $this->canSetPriority($subject, $user);
            case self::ACTIVATE:
                return $this->canActivate($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
            case self::PROMOTE:
                return $this->canPromote($subject, $user);
            case self::DEMOTE:
                return $this->canDemote($subject, $user);
        }
    }

    private function canSetPriority(UserProfileInterface $up, UserInterface $user)
    {
        return $up->getUser() == $user;
    }

    private function canActivate(UserProfileInterface $up, UserInterface $user)
    {
        return $up->getUser() == $user;
    }

    private function canDelete(UserProfileInterface $up, UserInterface $user)
    {
        return $up->getUser() == $user;
    }

    private function canPromote(UserProfileInterface $up, UserInterface $user)
    {
        foreach ($up->getProfile()->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user && $userProfile->getIsOwner()) {
                return true;
            }
        }

        return false;
    }

    private function canDemote(UserProfileInterface $up, UserInterface $user)
    {
        if ($up->getUser() != $user) {
            return false;
        }

        foreach ($up->getProfile()->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                if (!$userProfile->getIsOwner()) {
                    return false;
                }
            }
        }

        $ownersNb = 0;

        foreach ($up->getProfile()->getUserProfiles() as $userProfile) {
            if ($userProfile->getIsOwner()) {
                ++$ownersNb;
            }
        }

        if ($ownersNb <= 1) {
            return false;
        }

        return true;
    }
}
