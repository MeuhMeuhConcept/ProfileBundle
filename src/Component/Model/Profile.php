<?php

namespace MMC\Profile\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Profile implements ProfileInterface, UserProfileAccessorInterface
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @var ArrayCollection
     */
    protected $type;

    /**
     * @var ArrayCollection
     */
    protected $userProfiles;

    public function __construct()
    {
        $this->userProfiles = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
