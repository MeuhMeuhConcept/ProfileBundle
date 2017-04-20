<?php

namespace MMC\Profile\Component\Provider;

use Doctrine\ORM\EntityManager;

class UserProfileProvider implements UserProfileProviderInterface
{
    private $em;
    private $userProfileClassname;
    private $userClassname;
    private $profileClassname;

    public function __construct(
        EntityManager $em,
        string $userProfileClassname,
        string $userClassname,
        string $profileClassname
    ) {
        $this->em = $em;
        $this->userProfileClassname = $userProfileClassname;
        $this->userClassname = $userClassname;
        $this->profileClassname = $profileClassname;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserProfileByUsernameAndUuid($username, $uuid)
    {
        $user = $this->em
            ->getRepository($this->userClassname)
            ->findOneByUsername($username)
        ;

        $profile = $this->em
            ->getRepository($this->profileClassname)
            ->findOneByUuid($uuid)
        ;

        $userProfile = $this->em
            ->getRepository($this->userProfileClassname)
            ->findOneBy(['user' => $user, 'profile' => $profile])
        ;

        return $userProfile;
    }
}
