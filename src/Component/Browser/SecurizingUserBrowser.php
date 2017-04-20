<?php

namespace MMC\Profile\Component\Browser;

use MMC\Profile\Component\Browser\Exception\BrowserAccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurizingUserBrowser implements UserBrowserInterface
{
    private $userBrowser;
    private $authorizationChecker;

    public function __construct(
        UserBrowser $userBrowser,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->userBrowser = $userBrowser;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function browse(array $options)
    {
        if (isset($options['profile'])) {
            if (!$this->authorizationChecker->isGranted('CAN_BROWSE_USERS', $options['profile'])) {
                throw new BrowserAccessDeniedHttpException();
            }
        }

        return $this->userBrowser->browse($options);
    }

    public function createOptionsResolver()
    {
        return $this->userBrowser->createOptionsResolver($user);
    }
}
