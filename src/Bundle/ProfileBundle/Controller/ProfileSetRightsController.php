<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
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
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/promote/{uuid}/{username}", name="profile_bundle_show_promotions_profile")
     */
    public function showPromotions(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->getUserProfile($user, $profile);
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('AppBundle:Profile:promote.html.twig',
            [
                'users' => $users,
                'userProfile' => $up,
            ]
        );
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/demote/{uuid}/{username}", name="profile_bundle_show_demotion_profile")
     */
    public function showDemotion(ProfileInterface $profile, UserInterface $user)
    {
        return $this->templating->renderResponse('AppBundle:Profile:demote.html.twig',
            [
                'profile' => $profile,
                'user' => $user,
            ]
        );
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/addPromotion/{uuid}/{username}", name="profile_bundle_promote_profile")
     */
    public function promote(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->promoteUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @Route("/addDemotion/{uuid}/{username}", name="profile_bundle_demote_profile")
     */
    public function demote(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->demoteUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
