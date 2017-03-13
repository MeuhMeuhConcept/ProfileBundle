<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile/show", service="profile_bundle.profile_show_controller")
 */
class ProfileShowController
{
    private $templating;

    public function __construct(
        EngineInterface $templating
    ) {
        $this->templating = $templating;
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/{uuid}/{username}", name="profile_bundle_show_profile")
     */
    public function show(ProfileInterface $profile, UserInterface $user)
    {
        $up = $profile->getUserProfile($user);

        return $this->templating->renderResponse('AppBundle:Profile:profile.html.twig',
            ['userProfile' => $up, 'user' => $user]);
    }
}
