<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Fixtures\Entity;

use MMC\Profile\Component\Model\UserProfile as UserProfileModel;

class UserProfile extends UserProfileModel
{
    /**
     * @var \User
     */
    protected $user;

    /**
     * @var \Profile
     */
    protected $profile;
}
