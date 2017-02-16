<?php

namespace MMC\Profile\Bundle\ProfileBundle\Entity;

use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileAccessorInterface;
use MMC\Profile\Component\Model\UserProfileAccessorTrait;

/**
 * @ORM\Entity
 */
class User implements UserInterface, UserProfileAccessorInterface
{
    use UserProfileAccessorTrait;

    /**
     * @ORM\OneToMany(targetEntity="MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile", mappedBy="user")
     */
    protected $userProfiles;

    /**
     * @var string
     */
    protected $username;

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [];
    }
}
