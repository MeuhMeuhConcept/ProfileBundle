<?php

namespace MMC\Profile\Component\Browser;

use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Model\ProfileInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBrowser implements UserBrowserInterface
{
    private $em;
    private $userClassname;

    public function __construct(EntityManager $em, $userClassname)
    {
        $this->em = $em;
        $this->userClassname = $userClassname;
    }

    public function browse(array $options)
    {
        $resolver = $this->createOptionsResolver();
        $options = $resolver->resolve($options);

        $queryBuilder = $this->em->getRepository($this->userClassname)->createQueryBuilder('u')
            ->orderBy('u.'.$options['order_by'], $options['sort']);

        if ($options['profile']) {
            $queryBuilder
                ->addSelect('up')
                ->addSelect('p')
                ->leftJoin('u.userProfiles', 'up', 'WITH', 'up.profile = :profile')
                ->leftJoin('up.profile', 'p')
                ->setParameter('profile', $options['profile']);
        }

        if ($options['term']) {
            $queryBuilder->andWhere('u.username LIKE :term')
                ->setParameter('term', $options['term'].'%')
                ;
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
            'term' => null,
            'order_by' => 'username',
            'sort' => 'ASC',
       ]);

        $resolver->setAllowedTypes('profile', ['null', ProfileInterface::class]);
        $resolver->setAllowedTypes('term', ['null', 'string']);

        $resolver->setAllowedValues('order_by', ['username']);
        $resolver->setAllowedValues('sort', ['asc', 'desc', 'ASC', 'DESC']);

        return $resolver;
    }
}
