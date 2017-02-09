<?php

namespace MMC\Profile\Component\Model;

class Profile implements ProfileInterface, UserProfileAccessorInterface
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var array
     */
    protected $roles;

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
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }
}
