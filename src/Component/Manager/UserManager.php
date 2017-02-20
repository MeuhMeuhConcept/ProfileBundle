<?php

namespace MMC\Profile\Component\Manager;

use MMC\Profile\Component\Model\UserInterface;

class UserManager implements UserManagerInterface
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
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
}
