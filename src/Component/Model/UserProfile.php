<?php

namespace MMC\Profile\Component\Model;

class UserProfile implements UserProfileInterface
{
    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @var bool
     */
    protected $isOwner;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var \User
     */
    protected $user;

    /**
     * @var \Profile
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
