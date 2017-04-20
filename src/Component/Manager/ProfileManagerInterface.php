<?php

namespace MMC\Profile\Component\Manager;

use MMC\Profile\Component\Model\ProfileInterface;

interface ProfileManagerInterface
{
    /**
     * Persist a profile.
     */
    public function saveProfile(ProfileInterface $profile);

    /**
     * Make a flush.
     */
    public function flush();
}
