<?php

namespace MMC\Profile\Component\Provider;

use Doctrine\ORM\EntityManager;

class ProfileProvider implements ProfileProviderInterface
{
    private $em;
    private $profileClassname;

    public function __construct(
        EntityManager $em,
        $profileClassname
    ) {
        $this->em = $em;
        $this->profileClassname = $profileClassname;
    }

    /**
     * {@inheritdoc}
     */
    public function findProfileByUuid($uuid)
    {
        $profile = $this->em
            ->getRepository($this->profileClassname)
            ->findOneByUuid($uuid)
        ;

        return $profile;
    }
}
