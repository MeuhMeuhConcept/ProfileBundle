<?php

namespace MMC\Profile\Component\ParamConverter;

use MMC\Profile\Component\Model\UserProfileInterface;
use MMC\Profile\Component\Provider\UserProfileProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class UserProfileParamConverter implements ParamConverterInterface
{
    private $userProfileClassname;
    private $userProfileProvider;

    public function __construct(string $userProfileClassname, UserProfileProviderInterface $userProfileProvider)
    {
        $this->userProfileClassname = $userProfileClassname;
        $this->userProfileProvider = $userProfileProvider;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $userProfile = $this->userProfileProvider->findUserProfileByUsernameAndUuid($request->attributes->get('username'), $request->attributes->get('uuid'));

        return $request->attributes->set($configuration->getName(), $userProfile);
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        if ($class != $this->userProfileClassname && $class != UserProfileInterface::class) {
            return false;
        }

        return true;
    }
}
