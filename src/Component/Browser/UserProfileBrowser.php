<?php

namespace MMC\Profile\Component\Browser;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileBrowser implements UserBrowserInterface
{
    private $em;
    private $userProfileClassname;

    public function __construct(EntityManager $em, $userProfileClassname)
    {
        $this->em = $em;
        $this->userProfileClassname = $userProfileClassname;
    }

    public function browse(array $options)
    {
        $resolver = $this->createOptionsResolver();
        $options = $resolver->resolve($options);

        $queryBuilder = $this->em->getRepository($this->userProfileClassname)->createQueryBuilder('up')
            ->addSelect('up');

        if ($options['profile']) {
            $queryBuilder
                ->innerJoin('up.profile', 'p', 'WITH', 'up.profile = :profile')
                ->innerJoin('up.user', 'u')
                ->setParameter('profile', $options['profile']);

            if ($options['term']) {
                $queryBuilder->andWhere('u.username LIKE :term')
                    ->setParameter('term', $options['term'].'%')
                ;
            }
        }

        if ($options['user']) {
            $queryBuilder
                ->innerJoin('up.user', 'us', 'WITH', 'up.user = :user')
                ->innerJoin('up.profile', 'pr')
                ->setParameter('user', $options['user']);
        }

        $adapter = new DoctrineORMAdapter($queryBuilder);

        $pagerFanta = new Pagerfanta($adapter);
        $results = $pagerFanta->getCurrentPageResults();

        return new BrowserResponse(
            $results,
            $pagerFanta->getCurrentPage(),
            $pagerFanta->getNbResults(),
            $pagerFanta->getMaxPerPage(),
            $pagerFanta->getNbPages()
        );
    }

    public function createOptionsResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'profile' => null,
            'user' => null,
            'term' => null,
       ]);

        $resolver->setAllowedTypes('profile', ['null', ProfileInterface::class]);
        $resolver->setAllowedTypes('user', ['null', UserInterface::class]);

        $resolver->setAllowedTypes('term', ['null', 'string']);

        return $resolver;
    }
}
