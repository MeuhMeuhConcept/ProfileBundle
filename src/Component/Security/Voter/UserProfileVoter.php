<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserProfileVoter extends Voter
{
    const ACTIVATE = 'CAN_ACTIVATE_USERPROFILE';
    const DISSOCIATE = 'CAN_DISSOCIATE_USERPROFILE';
    const PROMOTE = 'CAN_PROMOTE_USERPROFILE';
    const DEMOTE = 'CAN_DEMOTE_USERPROFILE';
    const GET_USER_PROFILE = 'CAN_GET_USER_PROFILE';
    const GET_IS_OWNER = 'CAN_GET_IS_OWNER_USERPROFILE';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::ACTIVATE, self::DISSOCIATE, self::PROMOTE, self::DEMOTE, self::GET_USER_PROFILE, self::GET_IS_OWNER])) {
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
            case self::ACTIVATE:
                return $this->canActivate($subject, $user);
            case self::DISSOCIATE:
                return $this->canDissociate($subject, $user);
            case self::PROMOTE:
                return $this->canPromote($subject, $user);
            case self::DEMOTE:
                return $this->canDemote($subject, $user);
            case self::GET_USER_PROFILE:
                return $this->canGetUserProfile();
            case self::GET_IS_OWNER:
                return $this->canGetIsOwner();
        }
    }

    private function canActivate(UserProfileInterface $up, UserInterface $user)
    {
        return $up->getUser() == $user;
    }

    private function canDissociate(UserProfileInterface $up, UserInterface $user)
    {
        if ($up->getUser() == $user) {
            return !$up->getIsOwner();
        }

        if ($up->getIsOwner()) {
            return false;
        }

        $userProfile = $up->getProfile()->getUserProfile($user);
        if ($userProfile && $userProfile->getIsOwner()) {
            return true;
        }

        return false;
    }

    private function canPromote(UserProfileInterface $up, UserInterface $user)
    {
        $userProfile = $up->getProfile()->getUserProfile($user);
        if ($userProfile) {
            return $userProfile->getIsOwner();
        }

        return false;
    }

    private function canDemote(UserProfileInterface $up, UserInterface $user)
    {
        if ($up->getUser() != $user) {
            return false;
        }

        $userProfile = $up->getProfile()->getUserProfile($user);
        if ($userProfile && !$userProfile->getIsOwner()) {
            return false;
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

    private function canGetUserProfile()
    {
        return true;
    }

    private function canGetIsOwner()
    {
        return true;
    }
}
