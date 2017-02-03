Feature: UserProfileAccessor

@reset-schema
    Scenario: test UserProfileAccessorInterface functions
        Given there are the following profiles
            | uuid      |roles      |
            | 123456789 |ROLE_USER  |
            | 987654321 |ROLE_TEST  |
            | 000000000 |ROLE_USER  |
            | 454545454 |ROLE_TEST1 |
        And the following users
            | username |
            | toto     |
            | tintin   |
        And the following userProfiles
            # Empty lines correspond to false boolean value
            | profile   | user   | isActive | isOwner |
            | 123456789 | toto   |          | true    |
            | 123456789 | tintin |          | true    |
            | 000000000 | toto   |          |         |
            | 000000000 | tintin |          | true    |
            | 454545454 | toto   | true     |         |
            | 987654321 | tintin | true     | true    |
        Then I should see tintin active profile is 987654321
        And I should see 3 userProfiles for tintin

        Given tintin use profile 000000000
        Then I should see tintin active profile is 000000000

        Given toto use profile 000000000
        Then I should see toto active profile is 000000000

        And I should see that toto is owner of profile 123456789
        And I should see that tintin is owner of profile 987654321

        Given I remove profile 000000000 to toto
        Then I should see exception 403

        Given I remove profile 987654321 to tintin
        Then I should see 2 userProfiles for tintin
        And tintin use profile 987654321
        And I should see exception 404

        Given I remove profile 000000000 to tintin
        And I should see exception 403
