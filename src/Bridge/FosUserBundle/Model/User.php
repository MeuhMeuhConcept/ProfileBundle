<?php

namespace MMC\Profile\Bridge\FosUserBundle\Model;

use FOS\UserBundle\Model\User as FosUser;
use MMC\Profile\Component\Model\UserInterface;
use MMC\Profile\Component\Model\UserProfileAccessorInterface;
use MMC\Profile\Component\Model\UserProfileAccessorTrait;

class User extends FosUser implements UserInterface, UserProfileAccessorInterface
{
    use UserProfileAccessorTrait;
}
