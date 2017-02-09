<?php

namespace MMC\Profile\Component\Model;

use MMC\Profile\Component\Manipulator\UserProfileManipulator;

class User implements UserInterface, UserProfileAccessorInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var array
     */
    protected $userProfiles;

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
        if (($key = array_search($up, $this->userProfiles)) !== false) {
            unset($this->userProfiles[$key]);
        }

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
        $manipulator = new UserProfileManipulator();

        return $manipulator->getActiveProfile($this)->getRoles();
    }
}
