<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile/dissociate", service="profile_bundle.profile_dissociate_controller")
 */
class ProfileDissociateController
{
    private $templating;
    private $manipulator;
    private $upManager;
    private $userManager;
    private $router;

    public function __construct(
        EngineInterface $templating,
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        UserManagerInterface $userManager,
        Router $router
    ) {
        $this->templating = $templating;
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->userManager = $userManager;
        $this->router = $router;
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/show/{uuid}/{username}", name="profile_bundle_show_dissociations_profile")
     */
    public function showDissociations(UserProfileInterface $userProfile)
    {
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('MMCProfileBundle:Profile:dissociate.html.twig',
            [
                'users' => $users,
                'userProfile' => $userProfile,
            ]
        );
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/{uuid}/{username}", name="profile_bundle_dissociate_profile")
     */
    public function dissociate(UserProfileInterface $userProfile)
    {
        $up = $this->manipulator->removeProfileForUser($userProfile->getUser(), $userProfile->getProfile());

        $this->upManager->removeUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
