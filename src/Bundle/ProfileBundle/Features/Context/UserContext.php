<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MMC\Profile\Component\Manipulator\UserProfileManipulator;

class UserContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    protected $userProfileManipulator;

    public function __construct(userProfileManipulator $userProfileManipulator)
    {
        $this->userProfileManipulator = $userProfileManipulator;
    }

    /**
     * @Then I should see :arg1 active profile is :arg2
     */
    public function iShouldSeeActiveProfileIs($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $activeProfileUuid = $this->userProfileManipulator->getActiveProfile($user)->getUuid();
                \PHPUnit_Framework_Assert::assertEquals(
                    $arg2,
                    $activeProfileUuid
                );
            }
        }
    }

    /**
     * @Given :arg1 use profile :arg2
     */
    public function tintinUseProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $this->userProfileManipulator->setActiveProfile($user, $selectedProfile);
            }
        }
    }
}
