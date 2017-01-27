<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use MMC\Profile\Component\Model\User as UserModel;

/**
 * @ORM\Entity
 */
class User extends UserModel
{
    /**
     * @ORM\OneToMany(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile", mappedBy="user")
     */
    protected $userProfiles;
}
