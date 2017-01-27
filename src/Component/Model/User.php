<?php

namespace MMC\Profile\Component\Model;

abstract class User implements UserInterface, UserProfileAccessorInterface
{
    use UserProfileAccessorTrait;

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
        return $this->getActiveProfile()->getRoles();
    }
}
