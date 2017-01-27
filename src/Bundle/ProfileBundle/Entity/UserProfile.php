<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use MMC\Profile\Component\Model\UserProfile as UserProfileModel;

/**
 * @ORM\Entity
 */
class UserProfile extends UserProfileModel
{
    /**
   * @ORM\ManyToOne(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\User", inversedBy="userProfiles")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $user;

  /**
   * @ORM\ManyToOne(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\Profile")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $profile;
}
