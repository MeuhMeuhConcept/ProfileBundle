<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile", service="profile_bundle.profile_show_controller")
 */
class ProfileShowController
{
    private $templating;
    private $tokenStorage;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Route("/{uuid}", name="profile_bundle_show_profile")
     */
    public function show(ProfileInterface $profile)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $up = $profile->getUserProfile($user);

        return $this->templating->renderResponse('AppBundle:Profile:profile.html.twig',
            [
                'userProfile' => $up,
                'user' => $user,
            ]
        );
    }
}
