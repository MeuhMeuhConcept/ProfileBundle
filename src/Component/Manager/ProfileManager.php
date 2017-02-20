<?php

namespace MMC\Profile\Component\Manager;

use MMC\Profile\Component\Model\ProfileInterface;

class ProfileManager implements ProfileManagerInterface
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function saveProfile(ProfileInterface $profile)
    {
        $this->em->persist($profile);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->em->flush();
    }
}
