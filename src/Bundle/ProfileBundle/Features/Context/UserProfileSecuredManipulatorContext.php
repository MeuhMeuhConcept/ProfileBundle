<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MMC\Profile\Component\Manipulator\UserProfileManipulatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserProfileSecuredManipulatorContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    protected $userProfileSecuredManipulator;

    public function __construct(
        UserProfileManipulatorInterface $userProfileSecuredManipulator,
        TokenStorage $tokenStorage
    ) {
        $this->userProfileSecuredManipulator = $userProfileSecuredManipulator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Given I'm logged in with :username account
     */
    public function loggedIn($username)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            if ($user) {
                $token = new UsernamePasswordToken(
                    $user,
                    null,
                    'main',
                    $user->getRoles()
                );
                $this->tokenStorage->setToken($token);
            }
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I use profile :profile_id
     */
    public function iUseProfile($profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->tokenStorage->getToken()->getUser();

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->setActiveProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I should see :username active profile is :profile_id
     */
    public function iShouldSeeActiveProfileIs($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $activeProfileUuid = $this->userProfileSecuredManipulator->getActiveProfile($user)->getUuid();
            \PHPUnit_Framework_Assert::assertEquals(
                $profile_id,
                $activeProfileUuid
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I should see that :username is owner of profile :profile_id
     */
    public function iShouldSeeThatIsOwnerOfProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            \PHPUnit_Framework_Assert::assertTrue(
                $this->userProfileSecuredManipulator->isOwner($user, $profile)
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I set priority of userProfile :username :profile_id to :priority
     */
    public function iSetPriorityOfUserProfile($username, $profile_id, $priority)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->setProfilePriority($user, $profile, $priority);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I associate :username to :profile_id
     */
    public function iAssociateUserToProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->createUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I should see :nb userProfiles for :username
     */
    public function iShouldSeeUserprofilesFor($nb, $username)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            \PHPUnit_Framework_Assert::assertCount(
                intval($nb),
                $user->getUserProfiles()
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I promote :username for profile :profile_id
     */
    public function iPromoteUserForProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->promoteUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I demote :username for profile :profile_id
     */
    public function iDemoteUserForProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->demoteUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I should see that profile :profile_id has :nb owners
     */
    public function iShouldSeeThatProfileHasOwners($profile_id, $nb)
    {
        $this->lastException = null;
        try {
            $profile = $this->getProfile($profile_id);

            \PHPUnit_Framework_Assert::assertCount(
                intval($nb),
                $this->userProfileSecuredManipulator->getOwners($profile)
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I remove profile :profile_id to :username
     */
    public function iRemoveProfileTo($profile_id, $username)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileSecuredManipulator->removeProfileForUser($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I am logged out
     */
    public function userIsLoggedOut()
    {
        $this->tokenStorage->setToken(null);
    }
}
