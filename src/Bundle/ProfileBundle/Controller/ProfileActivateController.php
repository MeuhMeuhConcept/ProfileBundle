<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/profile/activate", service="profile_bundle.profile_activate_controller")
 */
class ProfileActivateController
{
    private $manipulator;
    private $upManager;
    private $router;

    public function __construct(
        UserProfileManipulatorInterface $manipulator,
        UserProfileManagerInterface $upManager,
        Router $router
    ) {
        $this->manipulator = $manipulator;
        $this->upManager = $upManager;
        $this->router = $router;
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/{uuid}/{username}", name="profile_bundle_activate_profile")
     */
    public function activate(UserProfileInterface $userProfile)
    {
        $up = $this->manipulator->setActiveProfile($userProfile->getUser(), $userProfile->getProfile());

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_show_profile',
            [
                'uuid' => $userProfile->getProfile()->getUuid(),
                'username' => $userProfile->getUser()->getUsername(),
            ]
        ));
    }
}
