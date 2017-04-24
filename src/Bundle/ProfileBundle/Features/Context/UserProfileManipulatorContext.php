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
     * @Given :username use profile :profile_id
     */
    public function userUseProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileManipulator->setActiveProfile($user, $profile);
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

            $activeProfileUuid = $this->userProfileManipulator->getActiveProfile($user)->getUuid();
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

            \PHPUnit_Framework_Assert::assertEquals(
                true,
                $this->userProfileManipulator->isOwner($user, $profile)
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

            $this->userProfileManipulator->removeProfileForUser($user, $profile);
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
     * @Then I should see that profile :profile_id has :nb owners
     */
    public function iShouldSeeThatProfileHasOwners($profile_id, $nb)
    {
        $this->lastException = null;
        try {
            $profile = $this->getProfile($profile_id);

            \PHPUnit_Framework_Assert::assertCount(
                intval($nb),
                $this->userProfileManipulator->getOwners($profile)
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I create the userProfile :username :profile_id
     */
    public function iCreateTheUserProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileManipulator->createUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I set type of profile :profile_id to :type
     */
    public function iSetTypeOfProfileToType($profile_id, $type)
    {
        $this->lastException = null;
        try {
            $profile = $this->getProfile($profile_id);

            $this->profileTypeValidator->validate($type);
            $profile->setType($type);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Then I should see profile :profile_id type is :type
     */
    public function iShouldSeeProfileTypeIs($profile_id, $type)
    {
        $this->lastException = null;
        try {
            $profile = $this->getProfile($profile_id);

            \PHPUnit_Framework_Assert::assertEquals(
                $profile->getType(),
                $type
            );
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I promote :username for profile :profile_id
     */
    public function iPromoteForProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileManipulator->promoteUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @Given I demote :username for profile :profile_id
     */
    public function iDemoteForProfile($username, $profile_id)
    {
        $this->lastException = null;
        try {
            $user = $this->getUser($username);

            $profile = $this->getProfile($profile_id);

            $this->userProfileManipulator->demoteUserProfile($user, $profile);
        } catch (\Exception $e) {
            $this->lastException = $e;
        }
    }
}
