<?php

namespace MMC\Profile\Component\ParamConverter;

use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Provider\UserProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class UserParamConverter implements ParamConverterInterface
{
    private $userClassname;
    private $userProvider;

    public function __construct(string $userClassname, UserProviderInterface $userProvider)
    {
        $this->userClassname = $userClassname;
        $this->userProvider = $userProvider;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $user = $this->userProvider->findUserByUsername($request->attributes->get('username'));

        return $request->attributes->set($configuration->getName(), $user);
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        if ($class != $this->userClassname && $class != UserInterface::class) {
            return false;
        }

        return true;
    }
}
