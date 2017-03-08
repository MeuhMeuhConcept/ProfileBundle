<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Component\Model\Profile as BaseProfile;

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
}
