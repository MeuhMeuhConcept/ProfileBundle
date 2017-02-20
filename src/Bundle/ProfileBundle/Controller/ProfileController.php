<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use AppBundle\Entity\Profile;
use AppBundle\Form\ProfileType;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class ProfileController
{
    private $templating;
    private $tokenStorage;
    private $manipulator;
    private $upManager;
    private $formFactory;
    private $router;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        FormFactory $formFactory,
        Router $router
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @Route("/createProfile", name="app_createProfile")
     */
    public function createAction(Request $request)
    {
        $profile = new Profile();

        $form = $this->formFactory->create(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->tokenStorage->getToken()->getUser();
            $up = $this->manipulator->createUserProfile($user, $profile);

            $this->upManager->saveUserProfile($up);

            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('app_homepage'));
        }

        return $this->templating->renderResponse('ProfileBundle:Profile:create.html.twig',
        ['form' => $form->createView()]);
    }
}
