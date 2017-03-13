<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Bundle\ProfileBundle\Form\PriorityType;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile/set", service="profile_bundle.profile_set_priority_controller")
 */
class ProfileSetPriorityController
{
    private $templating;
    private $manipulator;
    private $upManager;
    private $formFactory;
    private $router;

    public function __construct(
        EngineInterface $templating,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        FormFactory $formFactory,
        Router $router
    ) {
        $this->templating = $templating;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/{uuid}/{username}", name="profile_bundle_set_priority_profile")
     */
    public function setPriority(Request $request, ProfileInterface $profile, UserInterface $user)
    {
        foreach ($profile->getUserProfiles() as $userProfile) {
            if ($userProfile->getUser() == $user) {
                $up = $userProfile;
            }
        }

        $form = $this->formFactory->create(PriorityType::class, $up);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manipulator->setProfilePriority($user, $profile);
            $this->upManager->saveUserProfile($up);
            $this->upManager->flush();

            return new RedirectResponse($this->router->generate('profile_bundle_show_profile',
                ['uuid' => $profile->getUuid(), 'username' => $user->getUsername()]
            ));
        }

        return $this->templating->renderResponse('AppBundle:Profile:priority.html.twig',
            ['form' => $form->createView()]);
    }
}
