<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class DefaultController
{
    private $templating;
    private $tokenStorage;
    private $userManager;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        UserManagerInterface $userManager
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
    }

    public function indexAction()
    {
        $users = $this->userManager->findUsers();

        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }
}
