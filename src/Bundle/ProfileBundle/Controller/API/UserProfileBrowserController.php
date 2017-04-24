<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller\API;

use MMC\Profile\Component\Browser\UserProfileBrowserInterface;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("api/userProfiles", service="profile_bundle.api_user_profile_browser_controller")
 */
class UserProfileBrowserController
{
    private $userProfileBrowser;
    private $serializer;
    private $authorizationChecker;

    public function __construct(
        UserProfileBrowserInterface $userProfileBrowser,
        Serializer $serializer
    ) {
        $this->userProfileBrowser = $userProfileBrowser;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/by_profile/{uuid}", name="profile_bundle_browse_get_user_profiles_by_profile_uuid")
     * @ParamConverter("profile", class="MMC\Profile\Component\Model\ProfileInterface")
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
     * @ParamConverter("requestedUser", class="MMC\Profile\Component\Model\UserInterface")
     * @Method({"GET"})
     */
    public function browseWithUser(Request $request, UserInterface $requestedUser)
    {
        $results = $this->userProfileBrowser->browse(array_merge(
            $request->query->all(),
            [
                'user' => $requestedUser,
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-profile']]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/by_user_profile/{username}/{uuid}", name="profile_bundle_browse_get_user_profiles_by_user_profile")
     * @ParamConverter("userProfile", class="MMC\Profile\Component\Model\UserProfileInterface")
     * @Method({"GET"})
     */
    public function browseWithUserProfile(Request $request, UserProfileInterface $userProfile)
    {
        $results = $this->userProfileBrowser->browse(array_merge(
            $request->query->all(),
            [
                'user' => $userProfile->getUser(),
                'profile' => $userProfile->getProfile(),
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-user-profile']]);

        return new JsonResponse($json, 200, [], true);
    }
}
