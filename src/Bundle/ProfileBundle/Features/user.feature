Feature: User

@reset-schema
    Scenario: getting user profiles
        Given there are the following profiles
            | uuid      |roles      | type  |
            | 123456789 |ROLE_USER  | TYPE1 |
            | 987654321 |ROLE_TEST  | TYPE2 |
        And the following users
            | username |
            | toto     |
            | tintin   |
        And the following userProfiles
            # Empty lines correspond to false boolean value
            | profile   | user   | isActive | isOwner |
            | 123456789 | tintin | true     | true    |
            | 123456789 | toto   |          |         |
            | 987654321 | tintin |          | true    |
        Then I should see tintin active profile is 123456789

        Given tintin use profile 987654321
        Then I should see tintin active profile is 987654321

        Given toto use profile 123456789
        Then I should see toto active profile is 123456789
