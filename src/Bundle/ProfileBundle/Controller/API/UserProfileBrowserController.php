<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller\API;

use MMC\Profile\Component\Browser\UserProfileBrowser;
use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("api/userProfiles/browse", service="profile_bundle.api_user_profile_browser_controller")
 */
class UserProfileBrowserController
{
    private $userProfileBrowser;
    private $serializer;
    private $authorizationChecker;

    public function __construct(
        UserProfileBrowser $userProfileBrowser,
        Serializer $serializer,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->userProfileBrowser = $userProfileBrowser;
        $this->serializer = $serializer;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Security("is_granted('CAN_BROWSE_USER_PROFILES_BY_PROFILE', profile)")
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
     * @Security("is_granted('CAN_BROWSE_USER_PROFILES_BY_USER', requestedUser)")
     * @Route("/by_user/{username}", name="profile_bundle_browse_get_user_profiles_by_user_username")
     * @ParamConverter("requestedUser", class="AppBundle:User")
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
     * @ParamConverter("user", class="AppBundle:User")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Method({"GET"})
     */
    public function browseWithUserProfile(Request $request, UserInterface $user, ProfileInterface $profile)
    {
        if (!$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_USER', $user) && !$this->authorizationChecker->isGranted('CAN_BROWSE_USER_PROFILES_BY_PROFILE', $profile)) {
            throw new AccessDeniedHttpException();
        }

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
