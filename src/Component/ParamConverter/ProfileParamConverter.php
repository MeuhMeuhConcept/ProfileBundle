<?php

namespace MMC\Profile\Component\ParamConverter;

use MMC\Profile\Component\Model\ProfileInterface;
use MMC\Profile\Component\Provider\ProfileProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfileParamConverter implements ParamConverterInterface
{
    private $profileClassname;
    private $profileProvider;

    public function __construct(string $profileClassname, ProfileProviderInterface $profileProvider)
    {
        $this->profileClassname = $profileClassname;
        $this->profileProvider = $profileProvider;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $profile = $this->profileProvider->findProfileByUuid($request->attributes->get('uuid'));

        return $request->attributes->set($configuration->getName(), $profile);
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        if ($class != $this->profileClassname && $class != ProfileInterface::class) {
            return false;
        }

        return true;
    }
}
