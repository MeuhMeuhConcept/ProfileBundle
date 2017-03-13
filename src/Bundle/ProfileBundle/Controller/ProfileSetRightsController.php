<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Manager\UserManagerInterface;
use MMC\Profile\Component\Manager\UserProfileManagerInterface;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Templating\EngineInterface;

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
     */
    public function promote(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->getUserProfile($user, $profile);
        $users = $this->userManager->findUsers();

        return $this->templating->renderResponse('AppBundle:Profile:promote.html.twig',
            ['users' => $users, 'userProfile' => $up]);
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function demote(ProfileInterface $profile, UserInterface $user)
    {
        return $this->templating->renderResponse('AppBundle:Profile:demote.html.twig',
            ['profile' => $profile, 'user' => $user]);
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function addPromotion(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->promoteUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }

    /**
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function addDemotion(ProfileInterface $profile, UserInterface $user)
    {
        $up = $this->manipulator->demoteUserProfile($user, $profile);

        $this->upManager->saveUserProfile($up);
        $this->upManager->flush();

        return new RedirectResponse($this->router->generate('profile_bundle_homepage'));
    }
}
