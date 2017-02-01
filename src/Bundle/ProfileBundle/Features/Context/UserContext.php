<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use MMC\Profile\Bundle\ProfileBundle\Entity\Profile;
use MMC\Profile\Bundle\ProfileBundle\Entity\User;
use MMC\Profile\Bundle\ProfileBundle\Entity\UserProfile;

class UserContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given there are the following profiles
     */
    public function thereAreTheFollowingProfiles(TableNode $table)
    {
        foreach ($table as $row) {
            $profile = new Profile();
            $profile->setUuid($row['uuid']);
            $profile->setRoles($row['roles']);
            $this->store['profiles'][] = $profile;
        }
    }

    /**
     * @Given the following users
     */
    public function theFollowingUsers(TableNode $table)
    {
        foreach ($table as $row) {
            $user = new User();
            $user->setUsername($row['username']);
            $this->store['users'][] = $user;
        }
    }

    /**
     * @Given the following userProfiles
     */
    public function theFollowingUserprofiles(TableNode $table)
    {
        foreach ($table as $row) {
            $userProfile = new UserProfile();
            foreach ($this->store['profiles'] as $profile) {
                if ($profile->getUuid() == $row['profile']) {
                    $selectedProfile = $profile;
                }
            }

            foreach ($this->store['users'] as $user) {
                if ($user->getUsername() == $row['user']) {
                    $selectedUser = $user;
                }
            }

            $userProfile->setProfile($selectedProfile)
                ->setUser($selectedUser)
                ->setIsActive($row['isActive'])
                ->setIsOwner($row['isOwner'])
            ;

            $this->store['userProfiles'][] = $userProfile;
        }
    }

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
    public function tintinUseProfile($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                $selectedProfile = $profile;
            }
        }

        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $user->setActiveProfile($selectedProfile);
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
