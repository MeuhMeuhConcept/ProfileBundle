<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserProfileVoter extends Voter
{
    const CAN_ACTIVATE_USERPROFILE = 'CAN_ACTIVATE_USERPROFILE';
    const CAN_DISSOCIATE_USERPROFILE = 'CAN_DISSOCIATE_USERPROFILE';
    const CAN_PROMOTE_USERPROFILE = 'CAN_PROMOTE_USERPROFILE';
    const CAN_DEMOTE_USERPROFILE = 'CAN_DEMOTE_USERPROFILE';
    const CAN_GET_USER_PROFILE = 'CAN_GET_USER_PROFILE';
    const CAN_GET_IS_OWNER_USERPROFILE = 'CAN_GET_IS_OWNER_USERPROFILE';
    const CAN_SHOW_USERPROFILE = 'CAN_SHOW_USERPROFILE';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
            self::CAN_ACTIVATE_USERPROFILE,
            self::CAN_DISSOCIATE_USERPROFILE,
            self::CAN_PROMOTE_USERPROFILE,
            self::CAN_DEMOTE_USERPROFILE,
            self::CAN_GET_USER_PROFILE,
            self::CAN_GET_IS_OWNER_USERPROFILE,
            self::CAN_SHOW_USERPROFILE,
        ])) {
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
            case self::CAN_ACTIVATE_USERPROFILE:
                return $this->canActivate($subject, $user);
            case self::CAN_DISSOCIATE_USERPROFILE:
                return $this->canDissociate($subject, $user);
            case self::CAN_PROMOTE_USERPROFILE:
                return $this->canPromote($subject, $user);
            case self::CAN_DEMOTE_USERPROFILE:
                return $this->canDemote($subject, $user);
            case self::CAN_GET_USER_PROFILE:
                return $this->canGetUserProfile();
            case self::CAN_GET_IS_OWNER_USERPROFILE:
                return $this->canGetIsOwner();
            case self::CAN_SHOW_USERPROFILE:
                return $this->canShow($subject, $user);
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

    private function canShow(UserProfileInterface $up, UserInterface $user)
    {
        foreach ($up->getProfile()->getUserProfiles() as $up) {
            if ($up->getUser() == $user && $up->getIsOwner()) {
                return true;
            }
        }

        if ($up->getUser() == $user) {
            return true;
        }

        return false;
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
