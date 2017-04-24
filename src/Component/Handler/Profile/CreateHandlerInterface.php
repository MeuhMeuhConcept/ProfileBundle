<?php

namespace MMC\Profile\Component\Handler\Profile;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;

interface CreateHandlerInterface
{
    /*
     * Validate, create and persist an userProfile
     */
    public function create(ProfileInterface $profile, UserInterface $user, $andActive = false);
}
