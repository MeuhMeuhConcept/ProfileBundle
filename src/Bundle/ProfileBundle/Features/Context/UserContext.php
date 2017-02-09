<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class UserContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    /**
     * @Then I should see :arg1 active profile is :arg2
     */
    public function iShouldSeeActiveProfileIs($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $activeProfileUuid = $this->manipulator->getActiveProfile($user)->getUuid();
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
                $this->manipulator->setActiveProfile($user, $selectedProfile);
            }
        }
    }

    /**
     * @Then I should see :arg1 roles are :arg2
     */
    public function iShouldSeeRolesAre($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $userRoles = $user->getRoles();
                \PHPUnit_Framework_Assert::assertEquals(
                    $arg2,
                    $userRoles
                );
            }
        }
    }
}
