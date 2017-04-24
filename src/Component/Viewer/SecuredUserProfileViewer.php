<?php

namespace MMC\Profile\Component\Viewer;

use MMC\Profile\Component\Model\UserProfileInterface;
use MMC\Profile\Component\Viewer\Exception\ViewerAccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecuredUserProfileViewer implements UserProfileViewerInterface
{
    private $userProfileViewer;
    private $authorizationChecker;

    public function __construct(userProfileViewer $userProfileViewer, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->userProfileViewer = $userProfileViewer;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function show(UserProfileInterface $up)
    {
        if (!$this->authorizationChecker->isGranted('CAN_SHOW_USERPROFILE', $up)) {
            throw new ViewerAccessDeniedHttpException();
        }

        return $this->userProfileViewer->show($up);
    }
}
