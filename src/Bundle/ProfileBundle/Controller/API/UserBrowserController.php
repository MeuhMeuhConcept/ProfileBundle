<?php

namespace MMC\Profile\Bundle\ProfileBundle\Controller\API;

use MMC\Profile\Component\Browser\UserBrowser;
use MMC\Profile\Component\Model\ProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("api/users/browse", service="profile_bundle.api_user_browser_controller")
 */
class UserBrowserController
{
    private $userBrowser;
    private $serializer;

    public function __construct(UserBrowser $userBrowser, Serializer $serializer)
    {
        $this->userBrowser = $userBrowser;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="profile_bundle_browse_get_users")
     * @Method({"GET"})
     */
    public function browse(Request $request)
    {
        $results = $this->userBrowser->browse($request->query->all());
        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse']]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/by_profile/{uuid}", name="profile_bundle_browse_get_users_by_profile_uuid")
     * @ParamConverter("profile", class="AppBundle:Profile")
     * @Method({"GET"})
     */
    public function browseWithProfile(Request $request, ProfileInterface $profile)
    {
        $results = $this->userBrowser->browse(array_merge(
            $request->query->all(),
            [
                'profile' => $profile,
            ]
        ));

        $json = $this->serializer->serialize($results, 'json', ['groups' => ['browse', 'browse-with-user-profile', 'browse-with-profile']]);

        return new JsonResponse($json, 200, [], true);
    }
}
