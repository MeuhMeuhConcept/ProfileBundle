<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route(service="profile_bundle.default_controller")
 */
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

    /**
     * @Route("/", name="profile_bundle_homepage")
     */
    public function index()
    {
        $users = $this->userManager->findUsers();

        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('AppBundle:Default:index.html.twig',
            ['user' => $user, 'users' => $users]);
    }
}
