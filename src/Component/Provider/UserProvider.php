<?php

namespace MMC\Profile\Component\Provider;

use Doctrine\ORM\EntityManager;

class UserProvider implements UserProviderInterface
{
    private $em;
    private $userClassname;

    public function __construct(
        EntityManager $em,
        $userClassname
    ) {
        $this->em = $em;
        $this->userClassname = $userClassname;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername($username)
    {
        $user = $this->em
            ->getRepository($this->userClassname)
            ->findOneByUsername($username)
        ;

        return $user;
    }
}
