<?php

namespace MMC\Profile\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Profile implements ProfileInterface, UserProfileAccessorInterface
{
    use UserProfileAccessorTrait;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var ArrayCollection
     */
    protected $userProfiles;

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
        $this->initializeRoles();

        $this->roles[] = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        $this->initializeRoles();

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

    /**
     * {@inheritdoc}
     */
    public function getUserProfile(UserInterface $user)
    {
        foreach ($this->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                return $userProfile;
            }
        }

        return null;
    }

    public function initializeRoles()
    {
        if ($this->roles == null) {
            $this->roles = new ArrayCollection();
        }
    }
}
