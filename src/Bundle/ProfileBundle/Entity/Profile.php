<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use MMC\Profile\Component\Model\Profile as ProfileModel;

/**
 * @ORM\Entity
 */
class Profile extends ProfileModel
{
    /**
     * @ORM\OneToMany(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile", mappedBy="profile")
     */
    protected $userProfiles;
}
