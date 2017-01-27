<?php

namespace MMC\Profile\Component\Model;

trait ProfileUserAccessorTrait
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
    public function getOwners()
    {
        $owners = [];
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getIsOwner()) {
                $owners[] = $up->getUser();
            }
        }

        return $owners;
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner(UserProfileAccessorInterface $userProfileAccessor)
    {
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getUser() == $userProfileAccessor && $up->getIsOwner() == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function addUserProfile(UserProfileInterface $userProfile)
    {
        if ($this == $userProfile->getProfile()) {
            if ($this->getUserProfiles() != null) {
                if (in_array($userProfile, $this->getUserProfiles())) {
                    return $this;
                }
            } else {
                $this->userProfiles[] = $userProfile;
                $userProfile->setProfile($this);
            }
        } else {
            throw new Exception('Erreur dans l\'ajout de la liaison user-profile');
        }

        return $this;
    }
}
