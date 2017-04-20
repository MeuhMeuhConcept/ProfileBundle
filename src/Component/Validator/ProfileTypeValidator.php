<?php

namespace MMC\Profile\Component\Validator;

use MMC\Profile\Component\Provider\ProfileTypeProvider;
use MMC\Profile\Component\Validator\Exception\InvalidProfileTypeException;

class ProfileTypeValidator
{
    protected $validator;

    public function __construct(ProfileTypeProvider $provider)
    {
        $this->provider = $provider;
    }

    public function validate($profileType)
    {
        if (!in_array($profileType, $this->provider->getTypes())) {
            throw new InvalidProfileTypeException();
        }
    }
}
