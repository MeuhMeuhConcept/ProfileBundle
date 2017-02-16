<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityManager;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class HomeController
{
    private $templating;
    private $tokenStorage;
    private $manipulator;
    private $em;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        UserProfileManipulatorInterface $manipulator,
        EntityManager $em
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->manipulator = $manipulator;
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user]);
    }
}
