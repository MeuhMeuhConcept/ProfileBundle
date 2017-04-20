<?php

namespace MMC\Profile\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

trait UserProfileAccessorTrait
{
    /**
     * @var ArrayCollection
     * @Groups({"browse-with-user-profile"})
     */
    protected $userProfiles;

    /**
     * {@inheritdoc}
     */
    public function getUserProfiles()
    {
        $this->initializeUserProfile();

        return $this->userProfiles;
    }

    /**
     * {@inheritdoc}
     */
    public function addUserProfile(UserProfileInterface $up)
    {
        $this->initializeUserProfile();
        if (!$this->userProfiles->contains($up)) {
            $this->userProfiles->add($up);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserProfile(UserProfileInterface $up)
    {
        $this->initializeUserProfile();
        $this->userProfiles->removeElement($up);

        return $this;
    }

    /**
     * Initialize userProfiles as ArrayCollection if null.
     */
    protected function initializeUserProfile()
    {
        if ($this->userProfiles == null) {
            $this->userProfiles = new ArrayCollection();
        }
    }
}
