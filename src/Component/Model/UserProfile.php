<?php

namespace MMC\Profile\Component\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class UserProfile implements UserProfileInterface
{
    /**
     * @var bool
     * @Groups({"browse-with-user-profile"})
     */
    protected $isActive;

    /**
     * @var bool
     * @Groups({"browse"})
     */
    protected $isOwner;

    /**
     * @var int
     * @Groups({"browse-with-user-profile"})
     */
    protected $priority;

    /**
     * @var \User
     * @Groups({"browse-with-user"})
     */
    protected $user;

    /**
     * @var \Profile
     * @Groups({"browse-with-profile"})
     */
    protected $profile;

    /**
     * {@inheritdoc}
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsOwner()
    {
        return $this->isOwner;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsOwner($isOwner)
    {
        $this->isOwner = $isOwner;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user)
    {
        if ($user != $this->user) {
            $this->user = $user;

            $this->user->addUserProfile($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setProfile(ProfileInterface $profile)
    {
        if ($profile != $this->profile) {
            $this->profile = $profile;

            $this->profile->addUserProfile($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
