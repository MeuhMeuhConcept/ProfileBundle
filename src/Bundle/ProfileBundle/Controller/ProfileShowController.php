<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
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
     * @ParamConverter("profile", class="MMC\Profile\Component\Model\ProfileInterface")
     * @Route("/{uuid}", name="profile_bundle_show_profile")
     */
    public function showForMe(ProfileInterface $profile)
    {
        $up = $profile->getUserProfile($this->tokenStorage->getToken()->getUser());

        return $this->show($up);
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/{uuid}/{username}", name="profile_bundle_show_profile_for_user")
     */
    public function show(UserProfileInterface $userProfile)
    {
        return $this->userProfileViewer->show($userProfile);
    }
}
