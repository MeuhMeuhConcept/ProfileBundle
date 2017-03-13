<?php

namespace MMC\Profile\Component\Manager;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\UuidGenerator\RamseyUuidGenerator;

class ProfileManager implements ProfileManagerInterface
{
    private $em;
    private $uuidGenerator;

    public function __construct(EntityManager $em, RamseyUuidGenerator $uuidGenerator)
    {
        $this->em = $em;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function saveProfile(ProfileInterface $profile)
    {
        $profile->setUuid($this->uuidGenerator->generate());
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
