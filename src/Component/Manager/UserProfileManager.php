<?php

namespace MMC\Profile\Component\Manager;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\UserProfileInterface;

class UserProfileManager implements UserProfileManagerInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function saveUserProfile(UserProfileInterface $userProfile)
    {
        $this->em->persist($userProfile);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->em->flush();
    }
}
