<?php

namespace MMC\Profile\Component\Model;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $ups = [];
        if (isset($this->userProfiles)) {
            foreach ($this->userProfiles as $up) {
                if ($up->getDeletedAt() == null) {
                    $ups[] = $up;
                }
            }
        }

        return $ups;
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
        $isSet = false;
        $userProfiles = $this->getUserProfiles();
        foreach ($userProfiles as $up) {
            if ($up->getProfile() == $profile) {
                $up->setIsActive(true);
                $isSet = true;
                foreach ($userProfiles as $up) {
                    if ($up->getProfile() != $profile) {
                        $up->setIsActive(false);
                    }
                }
            }
        }

        if ($isSet == false) {
            throw new NotFoundHttpException('Le profil n\'existe pas');
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
            throw new AccessDeniedHttpException('Erreur dans l\'ajout de la liaison user-profile');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserProfile(UserProfileInterface $userProfile)
    {
        if ($this != $userProfile->getUser()) {
            throw new AccessDeniedHttpException('Erreur dans la suppression de la liaison user-profile');
        }

        if ($this->getUserProfiles() == null) {
            throw new NotFoundHttpException('La liaison n\'existe pas');
        }

        if (!in_array($userProfile, $this->getUserProfiles())) {
            throw new NotFoundHttpException('La liaison n\'existe pas');
        }

        if ($this->getActiveProfile() == $userProfile->getProfile()) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas supprimer le profil actif');
        }

        $userProfile->setPriority(0);
        $userProfile->setDeletedAt(date('Y-m-d H:i:s'));

        return $this;
    }
}
