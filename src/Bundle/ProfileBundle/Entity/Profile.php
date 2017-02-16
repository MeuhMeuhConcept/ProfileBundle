<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Component\Model\Profile as ProfileModel;

class Profile extends ProfileModel
{
    /**
     * @ORM\OneToMany(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile", mappedBy="profile")
     */
    protected $userProfiles;
}
