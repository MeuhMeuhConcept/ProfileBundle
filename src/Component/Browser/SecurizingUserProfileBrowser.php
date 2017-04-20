<?php

namespace MMC\Profile\Component\Browser;

use MMC\Profile\Component\Browser\Exception\BrowserAccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurizingUserProfileBrowser implements UserProfileBrowserInterface
{
    private $userProfileBrowser;
    private $authorizationChecker;

    public function __construct(
        UserProfileBrowserInterface $userProfileBrowser,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->userProfileBrowser = $userProfileBrowser;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function browse(array $options)
    {
        if (isset($options['profile']) && isset($options['user'])) {
            if (!$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_USER', $options['user']) && !$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_PROFILE', $options['profile'])) {
                throw new BrowserAccessDeniedHttpException();
            }
        } elseif (isset($options['profile'])) {
            if (!$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_PROFILE', $options['profile'])) {
                throw new BrowserAccessDeniedHttpException();
            }
        } else {
            if (!$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_USER', $options['user'])) {
                throw new BrowserAccessDeniedHttpException();
            }
        }

        return $this->userProfileBrowser->browse($options);
    }

    public function createOptionsResolver()
    {
        return $this->userProfileBrowser->createOptionsResolver($user);
    }
}
