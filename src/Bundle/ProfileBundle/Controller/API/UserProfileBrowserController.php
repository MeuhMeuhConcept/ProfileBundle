<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller\API;

use MMC\Profile\Component\Browser\UserProfileBrowser;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("api/userProfiles/browse", service="profile_bundle.api_user_profile_browser_controller")
 */
class UserProfileBrowserController
{
    private $userProfileBrowser;
    private $serializer;

    public function __construct(UserProfileBrowser $userProfileBrowser, Serializer $serializer)
    {
        $this->userProfileBrowser = $userProfileBrowser;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/by_profile/{uuid}", name="profile_bundle_browse_get_user_profiles_by_profile_uuid")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Method({"GET"})
     */
    public function browseWithProfile(Request $request, ProfileInterface $profile)
    {
        $results = $this->userProfileBrowser->browse(array_merge(
            $request->query->all(),
            [
                'profile' => $profile,
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-user']]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/by_user/{username}", name="profile_bundle_browse_get_user_profiles_by_user_username")
     * @ParamConverter("user", class="AppBundle:User")
     * @Method({"GET"})
     */
    public function browseWithUser(Request $request, UserInterface $user)
    {
        $results = $this->userProfileBrowser->browse(array_merge(
            $request->query->all(),
            [
                'user' => $user,
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-profile']]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/by_user_profile/{username}/{uuid}", name="profile_bundle_browse_get_user_profiles_by_user_profile")
     * @ParamConverter("user", class="AppBundle:User")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Method({"GET"})
     */
    public function browseWithUserProfile(Request $request, UserInterface $user, ProfileInterface $profile)
    {
        $results = $this->userProfileBrowser->browse(array_merge(
            $request->query->all(),
            [
                'user' => $user,
                'profile' => $profile,
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-user-profile']]);

        return new JsonResponse($json, 200, [], true);
    }
}
