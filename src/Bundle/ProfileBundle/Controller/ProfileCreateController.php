<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use AppBundle\Form\ProfileTypeTest;
use MMC\Profile\Bundle\ProfileBundle\Form\ProfileType;
use MMC\Profile\Component\Handler\Profile\CreateHandler;
use MMC\Profile\Component\Manipulator\Exception\InvalidProfileClassName;
use MMC\Profile\Component\Model\ProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile/create", service="profile_bundle.profile_create_controller")
 */
class ProfileCreateController
{
    private $templating;
    private $tokenStorage;
    private $upManager;
    private $formFactory;
    private $router;
    private $createHandler;
    private $profileClassname;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        FormFactory $formFactory,
        Router $router,
        CreateHandler $createHandler,
        $profileClassname
    ) {
        if (!is_subclass_of($profileClassname, ProfileInterface::class)) {
            throw new InvalidProfileClassName();
        }

        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->createHandler = $createHandler;
        $this->profileClassname = $profileClassname;
    }

    /**
     * @Route("", name="profile_bundle_create_profile_form")
     * @Method({"GET"})
     */
    public function form(Request $request)
    {
        $profile = new $this->profileClassname();

        $form = $this->formFactory->create(ProfileType::class, $profile);
        $formTest = $this->formFactory->create(ProfileTypeTest::class, $profile);

        return $this->templating->renderResponse('MMCProfileBundle:Profile:create.html.twig',
            [
                'form' => $form->createView(),
                'formTest' => $formTest->createView(),
            ]
        );
    }

    /**
     * @Route("", name="profile_bundle_create_profile")
     * @Method({"POST"})
     */
    public function create(Request $request)
    {
        $profile = new $this->profileClassname();

        $form = $this->formFactory->create(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->tokenStorage->getToken()->getUser();
            $this->createHandler->create($profile, $user);

            return new RedirectResponse($this->router->generate('profile_bundle_show_profile',
                [
                    'uuid' => $profile->getUuid(),
                    'username' => $user->getUsername(),
                ]
            ));
        }

        $formTest = $this->formFactory->create(ProfileTypeTest::class, $profile);
        $formTest->handleRequest($request);

        if ($formTest->isSubmitted() && $formTest->isValid()) {
            $this->createHandler->create($profile, $this->tokenStorage->getToken()->getUser());

            return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
        }

        return $this->templating->renderResponse('MMCProfileBundle:Profile:create.html.twig',
            [
                'form' => $form->createView(),
                'formTest' => $formTest->createView(),
            ]
        );
    }
}
