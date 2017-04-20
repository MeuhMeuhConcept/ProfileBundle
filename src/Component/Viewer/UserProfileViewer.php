<?php

namespace MMC\Profile\Component\Viewer;

use MMC\Profile\Component\Model\UserProfileInterface;
use Symfony\Component\Templating\EngineInterface;

class UserProfileViewer implements UserProfileViewerInterface
{
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function show(UserProfileInterface $up)
    {
        return $this->templating->renderResponse('MMCProfileBundle:Profile:profile.html.twig',
            [
                'userProfile' => $up,
                'user' => $up->getUser(),
            ]
        );
    }
}
