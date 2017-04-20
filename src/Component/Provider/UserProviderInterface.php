<?php

namespace MMC\Profile\Component\Provider;

interface UserProviderInterface
{
    /**
     * Get user entity by username.
     */
    public function findUserByUsername($username);
}
