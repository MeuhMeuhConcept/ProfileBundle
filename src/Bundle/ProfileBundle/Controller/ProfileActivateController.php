<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     */
    public function activate(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->setActiveProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_seeProfile',
            ['uuid' => $profile->getUuid(), 'username' => $user->getUsername()]
        ));
    }
}
