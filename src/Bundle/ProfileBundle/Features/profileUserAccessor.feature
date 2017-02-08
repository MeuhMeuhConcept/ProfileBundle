Feature: ProfileUserAccessor

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
            | tutu     |
        And the following userProfiles
            # Empty lines correspond to false boolean value
            | profile   | user   | isActive | isOwner |
            | 123456789 | toto   |          | true    |
            | 123456789 | tintin |          | true    |
            | 000000000 | toto   |          |         |
            | 000000000 | tintin |          |         |
            | 000000000 | tutu   |          | true    |
            | 454545454 | toto   | true     |         |
            | 987654321 | tintin | true     | true    |
        Then I should see 2 userProfiles for 123456789
        And I should see that tintin is owner of profile 123456789
        And I should see that profile 123456789 has 2 owners

        Then I should see 3 userProfiles for 000000000
        And I should see that tutu is owner of profile 000000000
        And I should see that profile 000000000 has 1 owners

        Then I should see 1 userProfiles for 454545454
        And I should see that profile 454545454 has 0 owners

