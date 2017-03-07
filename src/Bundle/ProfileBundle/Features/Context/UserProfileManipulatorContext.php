<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MMC\Profile\Component\Manipulator\UserProfileManipulator;
use MMC\Profile\Component\Validator\ProfileTypeValidator;

class UserProfileManipulatorContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    protected $userProfileManipulator;
    protected $profileTypeValidator;

    public function __construct(UserProfileManipulator $userProfileManipulator, ProfileTypeValidator $profileTypeValidator)
    {
        $this->userProfileManipulator = $userProfileManipulator;
        $this->profileTypeValidator = $profileTypeValidator;
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
                    $this->userProfileManipulator->setActiveProfile($user, $selectedProfile);
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
                    $this->userProfileManipulator->isOwner($user, $selectedProfile)
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
                    $this->userProfileManipulator->removeProfileForUser($user, $selectedProfile);
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
                if ($user->getUserProfiles() != null) {
                    foreach ($user->getUserProfiles() as $up) {
                        \PHPUnit_Framework_Assert::assertCount(
                            intval($arg1),
                            $user->getUserProfiles()
                        );
                    }
                } else {
                    \PHPUnit_Framework_Assert::assertCount(
                        intval($arg1),
                        []
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
                    $this->userProfileManipulator->getOwners($profile)
                );
            }
        }
    }

    /**
     * @Then I create the userProfile :ar1 :arg2
     */
    public function iCreateTheUserProfile($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $selectedUser = $user;
            }
        }

        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $this->userProfileManipulator->createUserProfile($selectedUser, $profile);
            }
        }
    }

    /**
     * @Given I set type of profile :arg1 to :arg2
     */
    public function iSetTypeOfProfileToType($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg1) {
                try {
                    $this->profileTypeValidator->validate($arg2);
                    $profile->setType($arg2);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Then I should see profile :arg1 type is :arg2
     */
    public function iShouldSeeProfileTypeIs($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg1) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $profile->getType(),
                    $arg2
                );
            }
        }
    }

    /**
     * @Given I promote :arg1 for profile :arg2
     */
    public function iPromoteForProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                try {
                    $this->userProfileManipulator->promoteUserProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }

    /**
     * @Given I demote :arg1 for profile :arg2
     */
    public function iDemoteForProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                try {
                    $this->userProfileManipulator->demoteUserProfile($user, $selectedProfile);
                } catch (\Exception $e) {
                    $this->lastException = $e;
                }
            }
        }
    }
}
