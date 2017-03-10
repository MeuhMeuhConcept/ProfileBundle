<?php

namespace MMC\Profile\Component\Security\Voter;

use MMC\Profile\Component\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditUserVoter extends Voter
{
    const GET_ACTIVE = 'CAN_GET_ACTIVE_PROFILE';

    protected function supports($attribute, $subject)
    {
        // if (!in_array($attribute, [self::GET_ACTIVE])) {
        //     return false;
        // }

        // if (!$subject instanceof UserInterface) {
        //     return false;
        // }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // $user = $token->getUser();

        // if (!$user instanceof UserInterface) {
        //     return false;
        // }

        switch ($attribute) {
            case self::GET_ACTIVE:
                return $this->canGetActive();
        }
    }

    private function canGetActive()
    {
        return true;
    }
}
