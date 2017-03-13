<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use AppBundle\Form\ProfileTypeTest;
use MMC\Profile\Bundle\ProfileBundle\Form\ProfileType;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\Exception\InvalidProfileClassName;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Validator\ProfileTypeValidator;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class ProfileCreateController
{
    private $templating;
    private $tokenStorage;
    private $manipulator;
    private $upManager;
    private $userManager;
    private $formFactory;
    private $router;
    private $profileTypeValidator;
    private $profileClassname;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        FormFactory $formFactory,
        Router $router,
        ProfileTypeValidator $profileTypeValidator,
        $profileClassname
    ) {
        if (!is_subclass_of($profileClassname, ProfileInterface::class)) {
            throw new InvalidProfileClassName();
        }

        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->profileTypeValidator = $profileTypeValidator;
        $this->profileClassname = $profileClassname;
    }

    // public function form(Request $request)
    // {
    //     $profile = new $this->profileClassname();

    //     $form = $this->formFactory->create(ProfileType::class, $profile);
    //     $formTest = $this->formFactory->create(ProfileTypeTest::class, $profile);

    //     return $this->templating->renderResponse('AppBundle:Profile:create.html.twig',
    //         ['form' => $form->createView(), 'formTest' => $formTest->createView()]);
    // }

    public function create(Request $request)
    {
        $profile = new $this->profileClassname();

        $form = $this->formFactory->create(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $this->createHandler->create($profile, $this->tokenStorage->getToken()->getUser());

            // A mettre dans le handler
            $this->profileTypeValidator->validate($profile->getType());

            $user = $this->tokenStorage->getToken()->getUser();
            $up = $this->manipulator->createUserProfile($user, $profile, true);

            $this->upManager->saveUserProfile($up);
            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('profile_bundle_seeProfile',
                ['uuid' => $profile->getUuid(), 'username' => $user->getUsername()]
            ));
        }

        $formTest = $this->formFactory->create(ProfileTypeTest::class, $profile);
        $formTest->handleRequest($request);

        if ($formTest->isSubmitted() && $formTest->isValid()) {
            $this->createHandler->create($profile, $this->tokenStorage->getToken()->getUser());

            return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
        }

        return $this->templating->renderResponse('AppBundle:Profile:create.html.twig',
            ['form' => $form->createView(), 'formTest' => $formTest->createView()]);
    }
}
