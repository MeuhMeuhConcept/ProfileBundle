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
 * @Route("/profile", service="profile_bundle.profile_set_rights_controller")
 */
class ProfileSetRightsController
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
     * @Route("/promote/{uuid}/{username}", name="profile_bundle_show_promotions_profile")
     */
    public function showPromotions(UserProfileInterface $userProfile)
    {
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('MMCProfileBundle:Profile:promote.html.twig',
            [
                'users' => $users,
                'userProfile' => $userProfile,
            ]
        );
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/demote/{uuid}/{username}", name="profile_bundle_show_demotion_profile")
     */
    public function showDemotion(UserProfileInterface $userProfile)
    {
        return $this->templating->renderResponse('MMCProfileBundle:Profile:demote.html.twig',
            [
                'profile' => $userProfile->getProfile(),
                'user' => $userProfile->getUser(),
            ]
        );
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/addPromotion/{uuid}/{username}", name="profile_bundle_promote_profile")
     */
    public function promote(UserProfileInterface $userProfile)
    {
        $up = $this->manipulator->promoteUserProfile($userProfile->getUser(), $userProfile->getProfile());

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }

    /**
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Route("/addDemotion/{uuid}/{username}", name="profile_bundle_demote_profile")
     */
    public function demote(UserProfileInterface $userProfile)
    {
        $up = $this->manipulator->demoteUserProfile($userProfile->getUser(), $userProfile->getProfile());

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
