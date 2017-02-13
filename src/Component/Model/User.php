<?php

namespace MMC\Profile\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;

class User implements UserInterface, UserProfileAccessorInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var ArrayCollection
     */
    protected $userProfiles;

    public function __construct()
    {
        $this->userProfiles = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserProfiles()
    {
        return $this->userProfiles;
    }

    /**
     * {@inheritdoc}
     */
    public function addUserProfile(UserProfileInterface $up)
    {
        $this->userProfiles[] = $up;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserProfile(UserProfileInterface $up)
    {
        $this->userProfiles->removeElement($up);

        return $this;
    }

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
