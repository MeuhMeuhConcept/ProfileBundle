<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Route("/profile", service="profile_bundle.profile_show_controller")
 */
class ProfileShowController
{
    private $templating;
    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(
        EngineInterface $templating,
        TokenStorage $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->templating = $templating;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
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

        if (!$this->authorizationChecker->isGranted('CAN_SHOW_USERPROFILE', $up)) {
            throw new AccessDeniedHttpException();
        }

        return $this->templating->renderResponse('AppBundle:Profile:profile.html.twig',
            [
                'userProfile' => $up,
                'user' => $user,
            ]
        );
    }
}
