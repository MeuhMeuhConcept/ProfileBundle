<?php

namespace MMC\Profile\Component\Manager;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\UserInterface;

class UserManager implements UserManagerInterface
{
    private $em;
    private $userClassname;

    public function __construct(EntityManager $em, $userClassname)
    {
        $this->em = $em;
        $this->userClassname = $userClassname;
    }

    /**
     * {@inheritdoc}
     */
    public function saveUser(UserInterface $user)
    {
        $this->em->persist($user);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findUsers()
    {
        return $this->em->getRepository($this->userClassname)->findAll();
    }
}
