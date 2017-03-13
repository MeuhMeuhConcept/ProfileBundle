<?php

namespace MMC\Profile\Component\Manager;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\UserProfileInterface;

class UserProfileManager implements UserProfileManagerInterface
{
    private $em;
    private $userManager;
    private $profileManager;

    public function __construct(EntityManager $em, UserManagerInterface $userManager, ProfileManagerInterface $profileManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->profileManager = $profileManager;
    }

    /**
     * {@inheritdoc}
     */
    public function saveUserProfile(UserProfileInterface $userProfile)
    {
        $this->userManager->saveUser($userProfile->getUser());
        $this->profileManager->saveProfile($userProfile->getProfile());
        $this->em->persist($userProfile);
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserProfile(UserProfileInterface $userProfile)
    {
        $this->em->remove($userProfile);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->em->flush();
    }
}
