<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Viewer\UserProfileViewerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Route("/profile", service="profile_bundle.profile_show_controller")
 */
class ProfileShowController
{
    private $tokenStorage;
    private $userProfileViewer;

    public function __construct(
        TokenStorage $tokenStorage,
        UserProfileViewerInterface $userProfileViewer
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userProfileViewer = $userProfileViewer;
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Route("/{uuid}", name="profile_bundle_show_profile")
     */
    public function showForMe(ProfileInterface $profile)
    {
        return $this->show($profile, $this->tokenStorage->getToken()->getUser());
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/{uuid}/{username}", name="profile_bundle_show_profile_for_user")
     */
    public function show(ProfileInterface $profile, UserInterface $user)
    {
        $up = $profile->getUserProfile($user);

        return $this->userProfileViewer->show($up);
    }
}
