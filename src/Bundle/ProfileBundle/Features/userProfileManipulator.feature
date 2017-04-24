Feature: UserProfileManipulator
@reset-schema
    Scenario: test UserProfileManipulator functions
        Given there are the following profiles
            | uuid      |roles      | type  |
            | 123456789 |ROLE_USER  | TYPE1 |
            | 987654321 |ROLE_TEST  | TYPE1 |
            | 000000000 |ROLE_USER  | TYPE2 |
            | 454545454 |ROLE_TEST1 | TYPE2 |
        And the following users
            | username |
            | toto     |
            | tintin   |
            | tutu     |
        And the following userProfiles
            # Empty lines correspond to false boolean value
            | profile   | user   | isActive | isOwner |
            | 123456789 | toto   |          | true    |
            | 123456789 | tintin |          |         |
            | 000000000 | toto   |          |         |
            | 000000000 | tintin |          | true    |
            | 454545454 | toto   | true     |         |
            | 987654321 | tintin | true     | true    |
            | 987654321 | toto   |          | true    |
        Then I should see tintin active profile is 987654321
        And I should see 3 userProfiles for tintin

        Given tintin use profile 000000000
        Then I should see tintin active profile is 000000000

        Given toto use profile 000000000
        Then I should see toto active profile is 000000000

        And I should see that toto is owner of profile 123456789
        And I should see that tintin is owner of profile 987654321

        # tutu has no userProfile
        Given I remove profile 000000000 to tutu
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\NoUserProfileException'
        Then I should see 0 userProfiles for tutu
        Given tutu use profile 000000000
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\NoUserProfileException'

        # try to use removed userProfile
        Given I remove profile 987654321 to tintin
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\UnableToDeleteOwnerUserProfileException'
        Then I remove profile 123456789 to tintin
        Then I should see 2 userProfiles for tintin
        And tintin use profile 123456789
        And I should see exception 'MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException'

        # tintin is owner of 987654321 profile
        Given I remove profile 987654321 to toto
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\UnableToDeleteOwnerUserProfileException'

        # tintin is owner of 000000000 profile
        Given I remove profile 000000000 to tintin
        And I should see exception 'MMC\Profile\Component\Manipulator\Exception\UnableToDeleteOwnerUserProfileException'

        Then I should see that profile 123456789 has 1 owners
        And I should see that profile 454545454 has 0 owners

        # userProfile tintin - 454545454 not exists
        Given  tintin use profile 454545454
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException'
        And I create the userProfile tintin 454545454
        And tintin use profile 454545454
        And I should see tintin active profile is 454545454

        Given I set type of profile 123456789 to TYPE2
        Then I should see profile 123456789 type is TYPE2

        # TYPE3 is not a valid value for profile type
        Given I set type of profile 123456789 to TYPE3
        Then I should see exception 'MMC\Profile\Component\Validator\Exception\InvalidProfileTypeException'
        Then I should see profile 123456789 type is TYPE2
        Then I should not see exception

        # tutu has no userProfile
        Given I promote tutu for profile 987654321
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\NoUserProfileException'
        Then I remove profile 454545454 to tintin
        Then I promote tintin for profile 454545454
        And I should see exception 'MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException'

        Given I promote toto for profile 000000000
        Then I should see that toto is owner of profile 000000000
        Then I should see that profile 000000000 has 2 owners

        # userProfile tintin - 123465789 not exists and tutu has no userProfile
        Given I demote tutu for profile 987654321
        Then I should see exception 'MMC\Profile\Component\Manipulator\Exception\NoUserProfileException'
        Then I demote tintin for profile 454545454
        And I should see exception 'MMC\Profile\Component\Manipulator\Exception\UserProfileNotFoundException'

        Given I demote toto for profile 000000000
        Then I should see that profile 000000000 has 1 owners