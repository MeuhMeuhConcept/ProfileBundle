<?php

namespace AppBundle\Entity;

use MMC\Profile\Component\Model\Profile as BaseProfile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="profile")
 */
class Profile extends BaseProfile
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    protected $id;

  /**
   * @ORM\OneToMany(targetEntity="UserProfile", mappedBy="profile")
   */
    protected $userProfiles;

    /**
     * @ORM\Column(type="string")
     */
    protected $uuid;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

}