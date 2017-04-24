<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use MMC\Profile\Bundle\ProfileBundle\Features\Fixtures\Entity\Profile;
use MMC\Profile\Bundle\ProfileBundle\Features\Fixtures\Entity\User;
use MMC\Profile\Bundle\ProfileBundle\Features\Fixtures\Entity\UserProfile;

abstract class GlobalContext implements Context, SnippetAcceptingContext
{
    protected $store;
    protected $lastException;

    public function __construct()
    {
        $this->store = [];
    }

    /**
     * @Given there are the following profiles
     */
    public function thereAreTheFollowingProfiles(TableNode $table)
    {
        foreach ($table as $row) {
            $profile = new Profile();
            $profile->setUuid($row['uuid']);
            $profile->setType($row['type']);
            $profile->addRole($row['roles']);
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
     * @Then /^I should see (?<count>\d+) (?<name>\w+)$/
     */
    public function iShouldSeeCountItem($count, $name)
    {
        \PHPUnit_Framework_Assert::assertCount(
            intval($count),
            $this->store[$name]
        );
    }

    /**
     * @Then I should not see exception
     */
    public function iShouldNotSeeException()
    {
        \PHPUnit_Framework_Assert::assertNull(
            $this->lastException
        );
    }

    /**
     * @Then I should see exception :exception
     */
    public function iShouldSeeException($exception)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $exception,
            get_class($this->lastException)
        );
    }

    /**
     * @Then I should see exception :exception with message :message
     */
    public function iShouldSeeExceptionAndMessage($exception, $message)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $exception,
            get_class($this->lastException)
        );

        \PHPUnit_Framework_Assert::assertEquals(
            $message,
            $this->lastException->getMessage()
        );
    }

    protected function getProfile($uuid)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $uuid) {
                return $profile;
            }
        }

        throw new \Exception('Profile not found');
    }

    protected function getUser($username)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $username) {
                return $user;
            }
        }

        throw new \Exception('User not found');
    }
}
