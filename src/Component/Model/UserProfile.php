<?php

namespace MMC\Profile\Component\Model;

abstract class UserProfile implements UserProfileInterface
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
    protected $piority;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(User $user)
    {
        if ($user != $this->user) {
            $this->user = $user;
            $user->addUserProfile($this);
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
    public function setProfile(Profile $profile)
    {
        if ($profile != $this->profile) {
            $this->profile = $profile;
            $profile->addUserProfile($this);
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
