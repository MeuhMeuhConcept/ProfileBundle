<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Templating\EngineInterface;

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
     */
    public function show(ProfileInterface $profile, UserInterface $user)
    {
        $up = $profile->getUserProfile($user);

        return $this->templating->renderResponse('AppBundle:Profile:profile.html.twig',
            ['userProfile' => $up, 'user' => $user]);
    }
}
