<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class UserProfileManipulatorContext extends GlobalContext implements Context, SnippetAcceptingContext
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
                    $this->manipulator->setActiveProfile($user, $selectedProfile);
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
                    $this->manipulator->isOwner($user, $selectedProfile)
                );
            }
        }
    }

    /**
     * @Given I remove profile :arg1 to :arg2
     */
    public function iRemoveProfileTo($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg1) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg2) {
                try {
                    $this->manipulator->removeProfileForUser($user, $selectedProfile);
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
                    \PHPUnit_Framework_Assert::assertCount(
                        intval($arg1),
                        $user->getUserProfiles()
                    );
                }
            }
        }
    }

    /**
     * @Then I should see that profile :arg1 has :arg2 owners
     */
    public function iShouldSeeThatProfileHasOwners($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg1) {
                \PHPUnit_Framework_Assert::assertCount(
                    intval($arg2),
                    $this->manipulator->getOwners($profile)
                );
            }
        }
    }

    /**
     * @Then I create the userProfile :ar1 :arg2
     */
    public function iCreateTheUserprofileTintin($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $selectedUser = $user;
            }
        }

        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $this->manipulator->createUserProfile($selectedUser, $profile);
            }
        }
    }
}
