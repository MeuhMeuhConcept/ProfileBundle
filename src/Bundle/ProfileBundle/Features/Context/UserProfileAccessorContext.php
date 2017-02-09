<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class UserProfileAccessorContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    /**
     * @Then I should see :arg1 active profile is :arg2
     */
    public function iShouldSeeActiveProfileIs($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $activeProfileUuid = $user->getActiveProfile()->getUuid();
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
    public function UseProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                try {
                    $user->setActiveProfile($selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see that :arg1 is owner of profile :arg2
     */
    public function iShouldSeeThatIsOwnerOfProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                \PHPUnit_Framework_Assert::assertEquals(
                    true,
                    $user->isOwner($selectedProfile)
                );
            }
        }
    }

    /**
     * @Given I remove profile :arg1 to :arg2
     */
    public function iRemoveProfileTo($arg1, $arg2)
    {
        $selectedUserProfile;
        foreach ($this->store['userProfiles'] as $up) {
            if ($up->getProfile()->getUuid() == $arg1 && $up->getUser()->getUsername() == $arg2) {
                $selectedUserProfile = $up;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $user->removeUserProfile($selectedUserProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see :arg1 userProfiles for :arg2
     */
    public function iShouldSeeUserprofilesFor($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                foreach ($user->getUserProfiles() as $up) {
                }
                \PHPUnit_Framework_Assert::assertCount(
                    intval($arg1),
                    $user->getUserProfiles()
                );
            }
        }
    }
}
