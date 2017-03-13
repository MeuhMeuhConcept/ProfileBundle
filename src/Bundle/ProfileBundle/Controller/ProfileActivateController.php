<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
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
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/{uuid}/{username}", name="profile_bundle_activate_profile")
     */
    public function activate(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->setActiveProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_show_profile',
            ['uuid' => $profile->getUuid(), 'username' => $user->getUsername()]
        ));
    }
}
