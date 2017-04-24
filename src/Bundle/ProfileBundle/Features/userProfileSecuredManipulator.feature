Feature: UserProfileSecuredManipulator
@reset-schema
    Scenario: test UserProfileSecuredManipulator functions
        Given there are the following profiles
            | uuid      |roles      | type  |
            | 123456789 |ROLE_USER  | TYPE1 |
            | 987654321 |ROLE_TEST  | TYPE1 |
            | 000000000 |ROLE_USER  | TYPE2 |
            | 454545454 |ROLE_TEST1 | TYPE2 |
            | 333333333 |ROLE_TEST  | TYPE1 |
            | 999999999 |ROLE_USER  | TYPE2 |
        And the following users
            | username |
            | toto     |
            | tintin   |
            | tutu     |
        And the following userProfiles
            # Empty lines correspond to false boolean value
            | profile   | user   | isActive | isOwner |
            | 123456789 | toto   |          | true    |
            | 123456789 | tutu   |          |         |
            | 000000000 | toto   |          |         |
            | 000000000 | tintin |          | true    |
            | 454545454 | toto   | true     |         |
            | 987654321 | tintin | true     | true    |
            | 987654321 | toto   |          | true    |
            | 333333333 | tutu   |          | true    |
            | 999999999 | toto   |          |         |
            | 999999999 | tintin |          |         |
            | 999999999 | tutu   | true     | true    |

            # userProfile tutu-000000000 not exists
            Given tutu is logged in
            Then tutu use profile 000000000
            And I should see exception ManipulatorAccessDeniedHttpException

            Given tutu use profile 333333333
            Then I should see tutu active profile is 333333333

            # can't set priority to an other user profile
            Given tutu set priority of userProfile tintin 000000000
            And I should see exception ManipulatorAccessDeniedHttpException

            # tutu is not owner of 123456789
            Given tutu associate tintin to 123456789
            Then I should see exception ManipulatorAccessDeniedHttpException
            Then tutu promote tintin for profile 123456789
            And I should see exception ManipulatorAccessDeniedHttpException

            Given tutu associate toto to 333333333
            Then I should see 6 userProfiles for toto
            Then tutu promote toto for profile 333333333
            And I should see that toto is owner of profile 333333333

            # tutu is not owner of 123456789
            Given tutu demote toto for profile 123456789
            Then I should see exception ManipulatorAccessDeniedHttpException

            # can't demote an other user - only personal demotion
            Given tutu demote toto for profile 123456789
            Then I should see exception ManipulatorAccessDeniedHttpException
            And I should see that profile 123456789 has 1 owners

            # can't demote if it's the last owner
            Given tutu demote tutu for profile 999999999
            Then I should see exception ManipulatorAccessDeniedHttpException
            And I should see that profile 999999999 has 1 owners

            # must promote an other user before to demote himself
            Given tutu promote toto for profile 999999999
            And I should see that profile 999999999 has 2 owners
            Then tutu demote tutu for profile 999999999
            And I should see that profile 999999999 has 1 owners

            # can't remove an other use profile
            Given tutu remove profile 123456789 to toto
            Then I should see exception ManipulatorAccessDeniedHttpException

            Then I should see 3 userProfiles for tutu
            Given tutu remove profile 999999999 to tutu
            Then I should see 2 userProfiles for tutu
