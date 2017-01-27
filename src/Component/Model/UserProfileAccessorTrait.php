<?php

namespace MMC\Profile\Component\Model;

trait UserProfileAccessorTrait
{
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
    public function getActiveProfile()
    {
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getIsActive() == 'true') {
                return $up->getProfile();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(Profile $profile)
    {
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getProfile() == $profile && $up->getIsOwner() == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setActiveProfile(Profile $profile)
    {
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getProfile() == $profile) {
                $up->setIsActive(true);
            } else {
                $up->setIsActive(false);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addUserProfile(UserProfileInterface $userProfile)
    {
        if ($this == $userProfile->getUser()) {
            if ($this->getUserProfiles() != null) {
                if (in_array($userProfile, $this->getUserProfiles())) {
                    return $this;
                } else {
                    $this->userProfiles[] = $userProfile;
                    $userProfile->setUser($this);
                }
            } else {
                $this->userProfiles[] = $userProfile;
                $userProfile->setUser($this);
            }
        } else {
            throw new Exception('Erreur dans l\'ajout de la liaison user-profile');
        }

        return $this;
    }
}
