<?php

namespace MMC\Profile\Component\Handler\Profile;

use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Validator\ProfileTypeValidator;

class CreateHandler implements CreateHandlerInterface
{
    public function __construct(
        ProfileTypeValidator $profileTypeValidator,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager
    ) {
        $this->profileTypeValidator = $profileTypeValidator;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(ProfileInterface $profile, UserInterface $user, $andActive = false)
    {
        $this->profileTypeValidator->validate($profile->getType());

        $up = $this->manipulator->createUserProfile($user, $profile);

        if ($andActive) {
            $this->manipulator->setActiveProfile($user, $profile);
        }

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();
    }
}
