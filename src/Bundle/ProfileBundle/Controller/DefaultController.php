<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class DefaultController
{
    private $templating;
    private $tokenStorage;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/", name="profile_bundle_homepage")
     */
    public function indexAction()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->templating->renderResponse('ProfileBundle:Default:index.html.twig',
            ['user' => $user]);
    }
}
