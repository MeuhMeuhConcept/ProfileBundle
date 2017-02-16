<?php

namespace AppBundle\Entity;

use MMC\Profile\Component\Model\UserProfile as BaseUserProfile;
use Doctrine\ORM\Mapping as ORM;

  /**
   * @ORM\Entity
   * @ORM\Table(name="user_profile")
   */
class UserProfile extends BaseUserProfile
{

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="userProfiles")
   * @ORM\JoinColumn(nullable=false)
   */
    protected $user;

  /**
   * @ORM\ManyToOne(targetEntity="Profile", inversedBy="userProfiles")
   * @ORM\JoinColumn(nullable=false)
   */
    protected $profile;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOwner;

    /**
     * @ORM\Column(type="integer")
     */
    protected $priority;
}
