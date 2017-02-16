<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Component\Model\UserProfile as UserProfileModel;

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
