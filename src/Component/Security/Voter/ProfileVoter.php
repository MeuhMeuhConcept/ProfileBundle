<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{
    const ASSOCIATE = 'CAN_ASSOCIATE_PROFILE';
    const GET_OWNERS = 'CAN_GET_OWNERS';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::ASSOCIATE, self::GET_OWNERS])) {
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
            case self::ASSOCIATE:
                return $this->canAssociate($subject, $user);
            case self::GET_OWNERS:
                return $this->canGetOwners();
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
}
