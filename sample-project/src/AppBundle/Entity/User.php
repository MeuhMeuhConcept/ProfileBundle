<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MMC\Profile\Bridge\FosUserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="UserProfile", mappedBy="user")
     * @ORM\OrderBy({"isActive" = "DESC", "priority" = "DESC"})
     */
    protected $userProfiles;
}