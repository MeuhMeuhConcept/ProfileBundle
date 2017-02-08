<?php

namespace MMC\Profile\Bundle\ProfileBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class ProfileUserAccessorContext extends GlobalContext implements Context, SnippetAcceptingContext
{
    /**
     * @Then I should see :arg1 userProfiles for :arg2
     */
    public function iShouldSeeUserprofilesFor($arg1, $arg2)
    {
        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                \PHPUnit_Framework_Assert::assertCount(
                    intval($arg1),
                    $profile->getUserProfiles()
                );
            }
        }
    }

    /**
     * @Then I should see that :arg1 is owner of profile :arg2
     */
    public function iShouldSeeThatIsOwnerOfProfile($arg1, $arg2)
    {
        foreach ($this->store['users'] as $user) {
            if ($user->getUsername() == $arg1) {
                $selectedUser = $user;
            }
        }

        foreach ($this->store['profiles'] as $profile) {
            if ($profile->getUuid() == $arg2) {
                \PHPUnit_Framework_Assert::assertEquals(
                    true,
                    $profile->isOwner($selectedUser)
                );
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
                    $profile->getOwners()
                );
            }
        }
    }
}
